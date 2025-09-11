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
use App\Entity\Settings;
use App\Enum\CronFrequency;
use App\Enum\OutpassStatus;
use App\Utils\CsvProcessor;
use App\Entity\ReportConfig;
use App\Services\UserService;
use App\Entity\OutpassRequest;
use App\Services\FacilityService;
use App\Entity\InstitutionProgram;
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
        private readonly FacilityService $facilityService,
        private readonly VerifierService $verifierService,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getDashboardDetails(User $adminUser): array
    {
        $counts = $this->outpass->getOutpassStats($adminUser);
        $checkedOut = $this->verifierService->fetchCheckedOutLogs($adminUser);
        $checkedIn = $this->verifierService->fetchCheckedInLogs($adminUser);
        $lockStatus = $this->em->getRepository(Settings::class)->findOneBy([
            'keyName' => 'lock_requests_' . strtolower($adminUser->getGender()->value)
        ]);

        return [
            'pending' => $counts['pending'] ?? 0,
            'approved' => $counts['approved'] ?? 0,
            'rejected' => $counts['rejected'] ?? 0,
            'checkedOut' => count($checkedOut),
            'checkedIn' => count($checkedIn),
            'lockRequests' => $lockStatus ? filter_var($lockStatus->getValue(), FILTER_VALIDATE_BOOLEAN) : false,
        ];
    }

    public function getLateArrivalsReport(User $adminUser): array
    {
        // Fetch late arrivals data
        $lateArrivals = $this->verifierService->fetchLateArrivals($adminUser);

        return [
            'total' => count($lateArrivals),
            'details' => $lateArrivals,
        ];
    }

    /**
     * Get all report settings
     *
     * @param User $adminUser
     * @return array
     */
    public function getAllReportSettings(User $adminUser): array
    {
        $settings = $this->em->getRepository(ReportConfig::class)->findBy([
            'gender' => $adminUser->getGender()
        ]);

        return $settings;
    }

    /**
     * Get report setting by ID
     *
     * @param int $reportId
     * @return ReportConfig|null
     */
    public function getReportSettingById(int $reportId): ReportConfig|null
    {
        return $this->em->getRepository(ReportConfig::class)->find($reportId);
    }

    /**
     * Toggle report status (enable/disable) by its Id
     * 
     * @param int $reportId
     * @return bool|ReportConfig
     */
    public function toggleReportStatus(int $reportId): bool|ReportConfig
    {
        $report = $this->getReportSettingById($reportId);

        if (!$report) {
            return false;
        }

        $report->setIsEnabled(!$report->isEnabled());
        $this->em->persist($report);
        $this->em->flush();

        return $report;
    }

    /**
     * Update report settings by its Id
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateReportSettingsById(int $id, array $data): bool
    {
        $report = $this->getReportSettingById($id);

        if (!$report) {
            return false;
        }

        $report->setFrequency(CronFrequency::from($data['frequency']));
        $report->setDayOfWeek($data['dayOfWeek']);
        $report->setDayOfMonth($data['dayOfMonth']);
        $report->setMonth($data['month']);
        $dateTime = DateTime::createFromFormat('H:i', $data['time']);

        if ($dateTime == false) {
            return false;
        }

        $report->setTime($dateTime);
        
        // Remove the recipients who are not super admin
        foreach($report->getRecipients() as $user) {
            if (!UserRole::isSuperAdmin($user->getRole()->value)) {
                $report->removeRecipient($user);
            }
        }

        // Add new recipients if got
        if (count($data['recipients']) > 0) {    
            foreach($data['recipients'] as $recipient) {
                $user = $this->userService->getUserById((int) $recipient);
                if (!$user instanceof User && !UserRole::isAdmin($user->getRole()->value)) {
                    return false;
                }
                $report->addRecipient($user);
            }
        }

        $this->em->persist($report);
        $this->em->flush();

        return true;
    }

    /**
     * Set Outpass Request Lock for Students
     *
     * @return boolean
     */
    public function setLockRequests(string $lockStatus, string $userGender): bool
    {
        $settings = $this->em->getRepository(Settings::class)->findOneBy([
            'keyName' => 'lock_requests_' . strtolower($userGender)
        ]);

        if (!$settings) {
            // Initialize if not present
            $settings = new Settings();
            $settings->setKeyName('lock_requests_' . strtolower($userGender));
            $settings->setValue('false');
            $this->em->persist($settings);
        }

        $newValue = strtolower($lockStatus) === 'true' ? 'true' : 'false';
        $settings->setValue($newValue);

        $this->em->persist($settings);
        $this->em->flush();

        return $newValue === 'true';
    }

    /**
     * Check if requests are currently locked
     *
     * @return bool
     */
    public function isRequestLock(string $userGender, ?Settings $settings = null): bool
    {
        if ($settings === null) {
            $settings = $this->em->getRepository(Settings::class)->findOneBy([
                'keyName' => 'lock_requests_' . strtolower($userGender)
            ]);
        }

        return $settings && $settings->getValue() === 'true';
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

    public function approvePending(OutpassRequest $outpass, $approvedBy): OutpassRequest|bool
    {
        $outpass->setStatus(OutpassStatus::APPROVED);
        $outpass->setRemarks(null);
        $outpass->setApprovedTime($time = new \DateTime());
        $outpass->setApprovedBy($approvedBy);

        $accepted = $this->view->renderEmail('outpass/accepted', [
            'studentName' => $outpass->getStudent()->getUser()->getName(),
            'outpass' => $outpass,
        ]);

        $userEmail = $outpass->getStudent()->getUser()->getEmail();
        $subject = "Your Outpass Request #{$outpass->getId()} Has Been Approved";

        $qrData = [
            'id' => $outpass->getId(),
            'type' => $outpass->getTemplate()->getName(),
            'student' => $userEmail,
        ];

        // Generate QR code and outpass document
        $qrCodePath = $this->outpass->generateQRCode($qrData);
        $outpass->setQrCode(basename($qrCodePath));

        $documentPath = $this->outpass->generateOutpassDocument($outpass);
        $outpass->setDocument(basename($documentPath));

        $attachments = [$documentPath, $qrCodePath];

        // Update outpass status
        $outpass = $this->outpass->updateOutpass($outpass);
        $queue = $this->mail->queueEmail(
            $subject,
            $accepted,
            $userEmail,
            $attachments
        );

        if (!$queue) {
            return false;
        }

        return $outpass;
    }

    public function rejectPending(OutpassRequest $outpass, $approvedBy, $reason=null): OutpassRequest|bool
    {
        $outpass->setStatus(OutpassStatus::REJECTED);
        $outpass->setApprovedTime($time = new \DateTime());
        $outpass->setApprovedBy($approvedBy);
        $outpass->setAttachments(null);

        if (empty($reason)) {
            $outpass->setRemarks(null);
        } else {
            $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
            $outpass->setRemarks($reason);
        }

        $rejected = $this->view->renderEmail('outpass/rejected', [
            'studentName' => $outpass->getStudent()->getUser()->getName(),
            'outpass' => $outpass,
        ]);

        $userEmail = $outpass->getStudent()->getUser()->getEmail();
        $subject = "Your Outpass Request #{$outpass->getId()} Has Been Rejected";

        // Update outpass status
        $outpass = $this->outpass->updateOutpass($outpass);
        $queue = $this->mail->queueEmail(
            $subject,
            $rejected,
            $userEmail
        );

        if (!$queue) {
            return false;
        }

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
        $required = ['name', 'program', 'email', 'digital_id', 'student_no', 'parent_no', 'hostel_name', 'room_no', 'year'];
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

                $program = $this->facilityService->getProgramByShortCode(trim($row['program'])) ?? null;

                if (!$program instanceof InstitutionProgram) {
                    $invalidUsers[] = "Row $rowNumber ($name): Program entity is null or invalid";
                    continue;
                }

                $hostel = $this->facilityService->getHostelByName($row['hostel_name']);

                if (!$hostel instanceof Hostel) {
                    $invalidUsers[] = "Row $rowNumber ($name): Invalid hostel name '{$row['hostel_name']}'";
                    continue;
                }

                $student = $this->userService->createStudent([
                    'email' => $email,
                    'year' => (int)($row['year'] ?? 1),
                    'program' => $program,
                    'hostel' => $hostel,
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