<?php

namespace App\Services;

use DateTime;
use Exception;
use App\Core\View;
use App\Entity\User;
use App\Core\Session;
use App\Core\Storage;
use App\Entity\Hostel;
use App\Enum\UserRole;
use App\Entity\Student;
use App\Core\JobDispatcher;
use App\Enum\OutpassStatus;
use App\Utils\CsvProcessor;
use App\Entity\AcademicYear;
use App\Jobs\GenerateQrCode;
use App\Services\UserService;
use App\Entity\OutpassRequest;
use App\Jobs\SendOutpassEmail;
use App\Core\JobPayloadBuilder;
use App\Entity\WardenAssignment;
use App\Jobs\GenerateOutpassPdf;
use App\Services\AcademicService;
use App\Entity\InstitutionProgram;
use App\Services\SystemSettingsService;
use Doctrine\ORM\EntityManagerInterface;

class AdminService
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly Storage $storage,
        private readonly MailService $mail,
        private readonly UserService $userService,
        private readonly OutpassService $outpass,
        private readonly AcademicService $academicService,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em,
        private readonly JobDispatcher $queue,
        private readonly SystemSettingsService $settingsService
    )
    {
    }

    public function getDashboardDetails(User $adminUser): array
    {
        $counts = $this->outpass->getOutpassStats($adminUser);
        $today = new DateTime('today');
        $checkedOut = $this->verifierService->fetchCheckedOutLogs($adminUser, $today);
        $checkedIn = $this->verifierService->fetchCheckedInLogs($adminUser, $today);
        $lockRequests = $this->getLockRequests();
        $genderKey = strtolower($adminUser->getGender()->value);

        return [
            'pending' => $counts['pending'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
            'checkedOut' => count($checkedOut),
            'checkedIn' => count($checkedIn),
            'lockRequests' => (bool) ($lockRequests[$genderKey] ?? false),
        ];
    }

    /**
     * Assign Warden to hostel
     *
     * @param User $warden
     * @param User $admin
     * @param array $assignmentData
     * @return bool
     */
    public function assignWarden(User $warden, User $admin, array $assignmentData): bool
    {
        if (!$warden || $warden->getRole() !== UserRole::ADMIN) {
            return false; // Invalid warden
        }

        foreach ($assignmentData as $hostelId) {
            $assignment = new WardenAssignment();
            $assignment->setAssignedTo($warden);
            $assignment->setAssignedBy($admin);
            $assignment->setHostelId((int) $hostelId);
            $assignment->setCreatedAt(new DateTime());

            $this->em->persist($assignment);
        }

        $this->em->flush();
        return true;
    }

    /**
     * Get Warden Assignments based on gender
     * @return array<int, array{assignment: WardenAssignment, resolvedTarget: ?object}>
     */
    public function getAssignmentsByGender(User $user): array
    {
        $assignments = $this->em->createQueryBuilder()
            ->select('wa', 'assignedBy', 'assignedTo')
            ->from(WardenAssignment::class, 'wa')
            ->join('wa.assignedBy', 'assignedBy')
            ->join('wa.assignedTo', 'assignedTo')
            ->where('assignedBy.gender = :gender')
            ->setParameter('gender', $user->getGender())
            ->getQuery()
            ->getResult();

        $views = [];
        foreach ($assignments as $wa) {
            $hostel = $this->em->getRepository(Hostel::class)->find($wa->getHostelId());
            $views[] = [
                'assignment' => $wa,
                'resolvedTarget' => $hostel,
            ];
        }
        return $views;
    }

    public function removeAssignment(int $assignmentId): bool
    {
        $assignment = $this->em->getRepository(WardenAssignment::class)->find($assignmentId);
        if (!$assignment) {
            return false;
        }

        $this->em->remove($assignment);
        $this->em->flush();

        return true;
    }

    public function getAssignedWardenForHostel(int $hostelId): ?User
    {
        $assignment = $this->em->getRepository(WardenAssignment::class)->findOneBy(
            ['hostelId' => $hostelId],
            ['createdAt' => 'DESC']
        );

        return $assignment?->getAssignedTo();
    }


    /**
     * Set Outpass Request Lock for Students
     *
     * @return boolean
     */
    public function setLockRequests(string $lockStatus, string $userGender): bool
    {
        $lockRequests = $this->getLockRequests();
        $genderKey = strtolower($userGender);
        $newValue = strtolower($lockStatus) === 'true';
        $lockRequests[$genderKey] = $newValue;
        $this->settingsService->set('lock_requests', $lockRequests);

        return $newValue;
    }

    /**
     * Check if requests are currently locked
     *
     * @return bool
     */
    public function isRequestLock(string $userGender): bool
    {
        $lockRequests = $this->getLockRequests();
        $genderKey = strtolower($userGender);
        return (bool) ($lockRequests[$genderKey] ?? false);
    }

    private function getLockRequests(): array
    {
        $defaults = [
            'male' => false,
            'female' => false,
        ];

        $lockRequests = $this->settingsService->get('lock_requests', $defaults);
        if (!is_array($lockRequests)) {
            return $defaults;
        }

        return array_merge($defaults, $lockRequests);
    }

    public function approveAllPending(User $approvedBy)
    {
        $pendingPass = $this->outpass->getPendingOutpass(paginate: false, warden: $approvedBy);
        
        try {
            foreach ($pendingPass as $pending) {
                $this->approvePending($pending, $approvedBy);
            }
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function approvePending(OutpassRequest $outpass, $approvedBy): OutpassRequest
    {
        // Update outpass status to approved
        $outpass->setStatus(OutpassStatus::APPROVED);
        $outpass->setRemarks(null);
        $outpass->setApprovedTime(new \DateTime());
        $outpass->setApprovedBy($approvedBy);

        // Persist update
        $outpass = $this->outpass->updateOutpass($outpass);

        // --- QR Job ---
        $qrJobPayload = JobPayloadBuilder::create()
            ->set('directory', 'qr_codes')
            ->set('prefix', 'qrcode_')
            ->set('size', 300)->set('margin', 10)
            ->set('outpass_id', $outpass->getId());

        $qrJob = $this->queue->dispatch(GenerateQrCode::class, $qrJobPayload);

        // --- PDF Job (depends on QR) ---
        $pdfJobPayload = JobPayloadBuilder::create()
            ->addDependency($qrJob->getId())
            ->set('directory', 'outpasses')
            ->set('prefix', 'outpass_')
            ->set('outpass_id', $outpass->getId());

        $pdfJob = $this->queue->dispatch(GenerateOutpassPdf::class, $pdfJobPayload);

        // --- Email Job (depends on PDF) ---
        $emailJobPayload = JobPayloadBuilder::create()
            ->addDependencies([$qrJob->getId(), $pdfJob->getId()])
            ->set('subject', "Your Outpass Request #{$outpass->getId()} Has Been Approved")
            ->set('to', $outpass->getStudent()->getUser()->getEmail())
            ->set('outpass_id', $outpass->getId())
            ->set('email_template', 'outpass/accepted');

        $this->queue->dispatch(SendOutpassEmail::class, $emailJobPayload);

        return $outpass;
    }

    public function rejectPending(OutpassRequest $outpass, $approvedBy, $reason=null): OutpassRequest
    {
        $outpass->setStatus(OutpassStatus::REJECTED);
        $outpass->setApprovedTime(new \DateTime());
        $outpass->setApprovedBy($approvedBy);
        $outpass->setAttachments(null);

        if (empty($reason)) {
            $outpass->setRemarks(null);
        } else {
            $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
            $outpass->setRemarks($reason);
        }

        // Update outpass status
        $outpass = $this->outpass->updateOutpass($outpass);

        // --- Email Job ---
        $emailJobPayload = JobPayloadBuilder::create()
            ->set('subject', "Your Outpass Request #{$outpass->getId()} Has Been Rejected")
            ->set('to', $outpass->getStudent()->getUser()->getEmail())
            ->set('outpass_id', $outpass->getId())
            ->set('email_template', 'outpass/rejected');
        $this->queue->dispatch(SendOutpassEmail::class, $emailJobPayload);

        return $outpass;
    }

    public function bulkUpload($uploadedFile, User $adminUser)
    {
        if (!$uploadedFile || $uploadedFile->getError() !== UPLOAD_ERR_OK) {
            $this->session->flash('error', ['bulk_upload' => 'File upload failed. Please try again.']);
            return false;
        }

        $filePath = $uploadedFile->getStream()->getMetadata('uri');

        $csv = new CsvProcessor();
        $parsed = $csv->readFromFile($filePath);

        // Validate headers
        $required = ['name', 'program', 'email', 'digital_id', 'student_no', 'parent_no', 'hostel_name', 'room_no', 'year', 'academic_year'];
        $headerValidation = $csv->validateHeaders($parsed['headers'], $required);

        // Set flash key for bulk upload
        $this->session->setCurrentFlashKey('bulk_upload');

        if (!$headerValidation['isValid']) {
            $missingList = implode(', ', $headerValidation['missing']);
            $this->session->flash('error', ["bulk_upload" => "Missing required headers: $missingList"]);
            return false;
        }

        $created = 0;
        $invalidUsers = [];
        $createdEmails = [];

        foreach ($parsed['data'] as $index => $row) {
            try {
                $rowNumber = $index + 2; // +2 to account for 0-based index and header row

                $email = strtolower(trim($row['email'] ?? ''));
                $programName = trim($row['program'] ?? '');
                $digitalId = trim($row['digital_id'] ?? '');
                $name = trim($row['name'] ?? '');

                // Validate email
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $invalidUsers[] = "Row $rowNumber ($name): Invalid email '$email'";
                    continue;
                }

                // Check if user already exists
                if (isset($createdEmails[$email]) || $this->userService->getUserByEmail($email) !== null) {
                    $invalidUsers[] = "Row $rowNumber ($name): User with email '$email' already exists";
                    continue;
                }

                // Validate program
                $program = $this->em->getRepository(InstitutionProgram::class)->findOneBy(['shortCode' => $programName]);

                if (!$program) {
                    $invalidUsers[] = "$email: Invalid program '$programName'";
                    continue;
                }

                // Validate digital ID and not exists already
                if (empty($digitalId) || !is_numeric($digitalId)) {
                    $invalidUsers[] = "Row $rowNumber ($name): Invalid digital ID '$digitalId'";
                    continue;
                }

                $existingStudent = $this->em->getRepository(Student::class)->findOneBy(['digitalId' => (int)$digitalId]);
                if ($existingStudent) {
                    $invalidUsers[] = "Row $rowNumber ($name): Student with digital ID '$digitalId' already exists";
                    continue;
                }

                $user = $this->userService->createUser([
                    'name' => $name,
                    'email' => $email,
                    'gender' => $adminUser->getGender(),
                    'contact' => $row['student_no'] ?? null,
                ]);

                if (!$user instanceof User) {
                    $invalidUsers[] = "Row $rowNumber ($name): User creation failed";
                    continue;
                }

                $program = $this->academicService->getProgramByShortCode(trim($row['program'])) ?? null;

                if (!$program instanceof InstitutionProgram) {
                    $invalidUsers[] = "Row $rowNumber ($name): Program entity is null or invalid";
                    continue;
                }

                $hostel = $this->academicService->getHostelByName($row['hostel_name']);

                if (!$hostel instanceof Hostel) {
                    $invalidUsers[] = "Row $rowNumber ($name): Invalid hostel name '{$row['hostel_name']}'";
                    continue;
                }

                $academicYearLabel = trim((string)($row['academic_year'] ?? ''));
                if ($academicYearLabel === '') {
                    $invalidUsers[] = "Row $rowNumber ($name): Missing academic year";
                    continue;
                }

                $academicYear = $this->academicService->getAcademicYearByLabel($academicYearLabel);
                if (!$academicYear instanceof AcademicYear) {
                    $invalidUsers[] = "Row $rowNumber ($name): Invalid academic year '$academicYearLabel'";
                    continue;
                }

                $student = $this->userService->createStudent([
                    'email' => $email,
                    'year' => (int)($row['year'] ?? 1),
                    'program' => $program,
                    'hostel' => $hostel,
                    'academic_year' => $academicYear,
                    'digital_id' => (int)$digitalId,
                    'room_no' => trim($row['room_no']) ?? null,
                    'parent_no' => trim($row['parent_no']) ?? null,
                ], $user);
                
                if (!$student instanceof Student) {
                    $invalidUsers[] = "Row $rowNumber ($name): Student creation failed";
                    continue;
                }
                
                $createdEmails[$email] = true;
                $created++;

            } catch (\Exception $e) {
                $invalidUsers[] = "$email: Unexpected error - " . $e->getMessage();
                continue;
            }
        }

        $this->em->flush();

        if (!empty($invalidUsers)) {
            $this->session->flash('error', [
                'bulk_upload' => "The following students were skipped:\n" . implode("\n", $invalidUsers)
            ]);
        } else {
            $this->session->flash('success', ["bulk_upload" => "$created students created successfully."]);
        }

        return true;
    }
}
