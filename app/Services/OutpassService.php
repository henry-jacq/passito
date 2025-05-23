<?php

namespace App\Services;

use DateTime;
use App\Core\View;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Enum\Gender;
use App\Core\Storage;
use App\Enum\UserRole;
use App\Entity\Student;
use App\Enum\OutpassType;
use Endroid\QrCode\QrCode;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use App\Entity\OutpassSettings;
use App\Entity\OutpassTemplate;
use Endroid\QrCode\Color\Color;
use App\Entity\OutpassTemplateField;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\ErrorCorrectionLevel;
use Doctrine\ORM\Tools\Pagination\Paginator;

class OutpassService
{
    public function __construct(
        private readonly View $view,
        private readonly Storage $storage,
        private readonly EntityManagerInterface $em
    )
    {
    }

    // Student methods
    public function getRecentStudentOutpass(Student $student, int $limit = 5)
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['student' => $student],
            ['createdAt' => 'DESC'],
            $limit
        );

        return $outpasses;
    }

    public function getOutpassByStudent(Student $student)
    {
        $outpasses = $this->em->getRepository(OutpassRequest::class)->findBy(
            ['student' => $student]
        );

        return $outpasses;
    }
    
    public function createOutpass(array $data): OutpassRequest
    {
        $outpass = new OutpassRequest();
        
        $outpass->setStudent($data['student']);
        $outpass->setTemplate($data['template']);
        $outpass->setFromDate($data['from_date']);
        $outpass->setToDate($data['to_date']);
        $outpass->setFromTime($data['from_time']);
        $outpass->setToTime($data['to_time']);
        $outpass->setStatus(OutpassStatus::PENDING);
        $outpass->setDestination($data['destination']);
        $outpass->setReason($data['reason'] ?? 'N/A');
        $outpass->setCustomValues($data['custom_values'] ?? null);
        $outpass->setCreatedAt(new DateTime());
        $outpass->setAttachments($data['attachments']);

        return $this->updateOutpass($outpass);
    }

    public function getOutpassById(int $id): ?OutpassRequest
    {
        return $this->em->getRepository(OutpassRequest::class)->find($id);
    }

    public function updateOutpass(OutpassRequest $outpass)
    {
        $this->em->persist($outpass);
        $this->em->flush();

        return $outpass;
    }

    public function getPendingOutpass(int $page = 1, int $limit = 10, bool $paginate = true, ?User $warden = null, ?string $hostelFilter = null)
    {
        $statuses = [OutpassStatus::PENDING->value];
        $wardenGender = $warden->getGender()->value;
        $settings = $this->getSettings($warden->getGender());

        if (Gender::isFemale($wardenGender) && $settings->getParentApproval()) {
            $statuses[] = OutpassStatus::PARENT_APPROVED->value;
        }

        if (!$paginate) {
            $queryBuilder = $this->em->createQueryBuilder();
            $queryBuilder->select('o')->from(OutpassRequest::class, 'o')
                ->join('o.student', 's')
                ->join('s.user', 'u')
                ->join('s.hostel', 'h')
                ->where($queryBuilder->expr()->in('o.status', ':statuses'))
                ->andWhere('u.gender = :gender')
                ->orderBy('o.createdAt', 'DESC')
                ->setParameter('statuses', $statuses)
                ->setParameter('gender', $wardenGender);

            // Optional hostel filtering for wardens
            if (UserRole::isAdmin($warden->getRole()->value)) {
                $hostels = $warden->getHostels();
                $ids = [];

                foreach ($hostels as $hostel) {
                    $ids[] = $hostel->getId();
                }

                if ($hostelFilter && $hostelFilter !== 'default') {
                    $queryBuilder->andWhere('h.id = :id')
                        ->setParameter('id', (int) $hostelFilter);
                } else {
                    $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                        ->setParameter('ids', $ids);
                }
            }

            return $queryBuilder->getQuery()->getResult();
        }

        $offset = ($page - 1) * $limit;

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(OutpassRequest::class, 'o')
            ->join('o.student', 's')
            ->join('s.user', 'u')
            ->join('s.hostel', 'h')
            ->where($queryBuilder->expr()->in('o.status', ':statuses'))
            ->andWhere('u.gender = :gender')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('statuses', $statuses)
            ->setParameter('gender', $wardenGender)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (UserRole::isAdmin($warden->getRole()->value)) {
            $hostels = $warden->getHostels();
            $ids = [];

            foreach ($hostels as $hostel) {
                $ids[] = $hostel->getId();
            }

            if ($hostelFilter && $hostelFilter !== 'default') {
                $queryBuilder->andWhere('h.id = :id')
                    ->setParameter('id', (int) $hostelFilter);
            } else {
                $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                    ->setParameter('ids', $ids);
            }
        }

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query, true);

        return [
            'data' => iterator_to_array($paginator),
            'total' => count($paginator),
            'currentPage' => $page,
            'totalPages' => ceil(count($paginator) / $limit),
        ];
    }

    // Admin methods

    /**
     * Get outpass statistics for the admin dashboard
     * @param User $adminUser
     */
    public function getOutpassStats(User $adminUser): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('o.status AS status', 'COUNT(o.id) AS count')
            ->from(OutpassRequest::class, 'o')
            ->join('o.student', 's')
            ->join('s.user', 'u')
            ->where('u.gender = :gender')
            ->setParameter('gender', $adminUser->getGender()->value)
            ->groupBy('o.status');

        $results = $qb->getQuery()->getArrayResult();

        $counts = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($results as $row) {
            $status = $row['status']->value;
            $count = (int) $row['count'];

            if ($status === 'approved' || $status === 'expired') {
                $counts['approved'] += $count;
            } elseif ($status === 'pending' || $status === 'parent_approved') {
                $counts['pending'] += $count;
            } elseif ($status === 'rejected') {
                $counts['rejected'] += $count;
            }
        }

        return $counts;
    }

    private function buildBaseOutpassQuery(?User $warden = null)
    {
        $statuses = [
            OutpassStatus::APPROVED->value,
            OutpassStatus::EXPIRED->value,
        ];

        $wardenGender = $warden->getGender()->value;

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(OutpassRequest::class, 'o')
            ->join('o.student', 's')
            ->join('s.user', 'u')
            ->where($queryBuilder->expr()->in('o.status', ':statuses'))
            ->andWhere('u.gender = :gender')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('statuses', $statuses)
            ->setParameter('gender', $wardenGender);

        return $queryBuilder;
    }

    public function getOutpassRecords(int $page = 1, int $limit = 10, ?User $warden = null)
    {
        $offset = ($page - 1) * $limit;

        $queryBuilder = $this->buildBaseOutpassQuery($warden)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query, true);

        return [
            'data' => iterator_to_array($paginator),
            'total' => count($paginator),
            'currentPage' => $page,
            'totalPages' => ceil(count($paginator) / $limit),
        ];
    }

    public function searchOutpassRecords(string $query, ?User $warden = null, int $limit = 10)
    {
        $qb = $this->buildBaseOutpassQuery($warden);

        // Apply custom search filters on joined tables (e.g., student name, course, etc.)
        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('u.name', ':search'),
                $qb->expr()->like('s.digitalId', ':search'),
                $qb->expr()->like('s.branch', ':search'),
                $qb->expr()->like('s.course', ':search'),
                $qb->expr()->like('o.destination', ':search')
            )
        )
            ->setParameter('search', '%' . $query . '%')
            ->setMaxResults($limit);

        $results = $qb->getQuery()->getResult();

        return [
            'data' => array_map(function (OutpassRequest $o) {
                return [
                    'id' => $o->getId(),
                    'student_name' => $o->getStudent()->getUser()->getName(),
                    'year' => $o->getStudent()->getYear(),
                    'course' => $o->getStudent()->getCourse(),
                    'branch' => $o->getStudent()->getBranch(),
                    'type' => $o->getTemplate()->getName(),
                    'destination' => $o->getDestination(),
                    'status' => $o->getStatus()->value,
                    'depart_date' => $o->getFromDate()?->format('d M, Y'),
                    'depart_time' => $o->getFromTime()?->format('h:i A'),
                    'return_date' => $o->getToDate()?->format('d M, Y'),
                    'return_time' => $o->getToTime()?->format('h:i A'),
                ];
            }, $results),
            'total' => count($results),
        ];
    }


    public function getSettings(Gender $gender)
    {
        $settings = $this->em->getRepository(OutpassSettings::class)
            ->findBy(['type' => $gender]);

        // Return the first element if there's only one, otherwise return the array
        return count($settings) === 1 ? $settings[0] : $settings;
    }

    public function updateSettings(User $user, array $data)
    {
        $settings = $this->em->getRepository(OutpassSettings::class)
            ->findOneBy(['type' => $user->getGender()]);

        // Check if "reset_defaults" is set to "true"
        if (!empty($data['reset_defaults']) && $data['reset_defaults'] === 'true') {
            // Reset to default values based on gender
            $defaultValues = $this->getDefaultValues($user->getGender()->value);

            $settings->setDailyLimit($defaultValues['dailyLimit']);
            $settings->setWeeklyLimit($defaultValues['weeklyLimit']);
            $settings->setWeekdayCollegeHoursStart($defaultValues['weekdayCollegeHoursStart']);
            $settings->setWeekdayCollegeHoursEnd($defaultValues['weekdayCollegeHoursEnd']);
            $settings->setWeekdayOvernightStart($defaultValues['weekdayOvernightStart']);
            $settings->setWeekdayOvernightEnd($defaultValues['weekdayOvernightEnd']);
            $settings->setWeekendStartTime($defaultValues['weekendStartTime']);
            $settings->setWeekendEndTime($defaultValues['weekendEndTime']);
            $settings->setParentApproval($defaultValues['parentApproval'] ?? false);
            $settings->setCompanionVerification($defaultValues['companionVerification'] ?? false);
            $settings->setEmergencyContactNotification($defaultValues['emergencyContactNotification']);
            $settings->setAppNotification($defaultValues['appNotification']);
            $settings->setEmailNotification($defaultValues['emailNotification']);
            $settings->setSmsNotification($defaultValues['smsNotification']);
        } else {
            // Helper function to convert time strings to DateTime or null
            $convertToTime = function (?string $timeString): ?\DateTime {
                return $timeString ? \DateTime::createFromFormat('H:i', $timeString) : null;
            };

            // Update settings fields with appropriate conversions
            $settings->setDailyLimit(!empty($data['daily_limit']) ? (int) $data['daily_limit'] : null);
            $settings->setWeeklyLimit(!empty($data['weekly_limit']) ? (int) $data['weekly_limit'] : null);
            $settings->setWeekdayCollegeHoursStart($convertToTime($data['weekday_college_hours_start'] ?? null));
            $settings->setWeekdayCollegeHoursEnd($convertToTime($data['weekday_college_hours_end'] ?? null));
            $settings->setWeekdayOvernightStart($convertToTime($data['weekday_overnight_start'] ?? null));
            $settings->setWeekdayOvernightEnd($convertToTime($data['weekday_overnight_end'] ?? null));
            $settings->setWeekendStartTime($convertToTime($data['weekend_start_time'] ?? null));
            $settings->setWeekendEndTime($convertToTime($data['weekend_end_time'] ?? null));
            $settings->setParentApproval(!empty($data['parent_approval']));
            $settings->setCompanionVerification(!empty($data['companion_verification']));
            $settings->setEmergencyContactNotification(!empty($data['emergency_contact_notification']));
            $settings->setAppNotification(!empty($data['app_notification']));
            $settings->setEmailNotification(!empty($data['email_notification']));
            $settings->setSmsNotification(!empty($data['sms_notification']));
        }

        // Persist and flush changes to database
        $this->em->persist($settings);
        $this->em->flush();

        return $settings;
    }

    private function getDefaultValues(string $gender): array
    {
        if ($gender === 'male') {
            return [
                'dailyLimit' => null,
                'weeklyLimit' => null,
                'weekdayCollegeHoursStart' => new \DateTime('09:00'),
                'weekdayCollegeHoursEnd' => new \DateTime('17:00'),
                'weekdayOvernightStart' => new \DateTime('22:00'),
                'weekdayOvernightEnd' => new \DateTime('06:00'),
                'weekendStartTime' => new \DateTime('09:00'),
                'weekendEndTime' => new \DateTime('23:59:59'),
                'emergencyContactNotification' => false,
                'appNotification' => true,
                'emailNotification' => true,
                'smsNotification' => false,
            ];
        }

        // Default values for female
        return [
            'dailyLimit' => null,
            'weeklyLimit' => null,
            'weekdayCollegeHoursStart' => new \DateTime('09:00'),
            'weekdayCollegeHoursEnd' => new \DateTime('17:00'),
            'weekdayOvernightStart' => new \DateTime('20:00'),
            'weekdayOvernightEnd' => new \DateTime('06:00'),
            'weekendStartTime' => new \DateTime('09:00'),
            'weekendEndTime' => new \DateTime('22:00'),
            'parentApproval' => true,
            'companionVerification' => false,
            'emergencyContactNotification' => false,
            'appNotification' => true,
            'emailNotification' => true,
            'smsNotification' => true,
        ];
    }

    /**
     * Generate a QR code for the given data
     */
    public function generateQRCode(mixed $data, int $size = 300, int $margin = 10): string
    {
        try {
            if (is_array($data)) {
                $data = json_encode($data);
            }

            // Encrypt the data
            $data = $this->encryptQrData($data, $_ENV['QR_SECRET']);
            
            $qrCode = new QrCode(
                $data, // Data to encode in the QR code
                new Encoding('UTF-8'), // Encoding
                ErrorCorrectionLevel::High, // Error correction level
                $size, // Size
                $margin, // Margin
                RoundBlockSizeMode::Margin, // Round block size mode
                new Color(0, 0, 0), // Foreground color (black)
                new Color(255, 255, 255) // Background color (white)
            );

            // Generate the QR code image data
            $writer = new PngWriter();
            $imageData = $writer->write($qrCode)->getString();

            // Generate unique file name with path
            $qrCodePath = $this->storage->generateFileName('qr_codes', 'png');

            // Save the QR code image
            $this->storage->write($qrCodePath, $imageData);

            return $qrCodePath;
    
        } catch (\Exception $e) {
            // Handle exceptions
            error_log('QR Code generation failed: ' . $e->getMessage());
            echo 'QR Code generation failed: ' . $e->getMessage();
        }
    }

    /**
     * Generate outpass document and return the file path
     */
    public function generateOutpassDocument(OutpassRequest $outpass): string
    {
        // Render Outpass document HTML
        $html = $this->view->renderEmail('outpass/document', [
            'outpass' => $outpass,
            'student' => $outpass->getStudent(),
        ]);

        // Change the working directory to storage path
        $options = new Options();
        // To get warden signature image
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath(STORAGE_PATH));

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF
        $output = $dompdf->output();

        // Generate unique file name with path
        $pdfPath = $this->storage->generateFileName('outpasses', 'pdf');

        // Save the PDF to a file
        $this->storage->write($pdfPath, $output);

        return $pdfPath;
    }

    /**
     * Remove the QR code file from storage
     */
    public function removeQrCode(OutpassRequest $outpass)
    {
        $qrCode = $outpass->getQrCode();
        return $this->storage->removeFile("qr_codes/{$qrCode}");
    }

    /**
     * Remove the outpass document file from storage
     */
    public function removeOutpassDocument(OutpassRequest $outpass)
    {
        $document = $outpass->getDocument();
        return $this->storage->removeFile("outpasses/{$document}");
    }

    /**
     * Remove the attachments from storage
     */
    public function removeAttachments(OutpassRequest $outpass)
    {
        $attachments = $outpass->getAttachments();

        foreach ($attachments as $attachment) {
            $this->storage->removeFile($attachment);
        }
    }

    /**
     * Check and expire outpasses that have passed their expiry date
     */
    public function checkAndExpireOutpass(int $batchSize = 20): void
    {
        $now = new \DateTimeImmutable();

        // Fetch all approved outpasses in one query
        // NOTE: Ensure the CONCAT function is supported by the database
        // Periodically Remove the outpass set as expired after check in
        // Also do the same as before periodical check expired outpasses and remove
        $outpasses = $this->em->getRepository(OutpassRequest::class)
        ->createQueryBuilder('o')
        ->where('o.status IN (:status)')
        ->andWhere("CONCAT(o.toDate, ' ', o.toTime) <= :now")
        ->setParameter('status', [OutpassStatus::APPROVED->value, OutpassStatus::EXPIRED->value])
        ->setParameter('now', $now->format('Y-m-d H:i:s'))
        ->getQuery()
        ->getResult();

        $count = 0;

        foreach ($outpasses as $outpass) {
            // Mark as expired
            if ($outpass->getStatus() === OutpassStatus::APPROVED) {
                $outpass->setStatus(OutpassStatus::EXPIRED);
            }

            // Remove the attachments
            if (!empty($outpass->getAttachments())) {
                $this->removeAttachments($outpass);
                $outpass->setAttachments([]);
            }
            
            // Remove the document and update the outpass record
            if (!empty($outpass->getDocument())) {
                $this->removeOutpassDocument($outpass);
                $outpass->setDocument(null);
            }

            if (!empty($outpass->getQrCode())) {
                $this->removeQrCode($outpass);
                $outpass->setQrCode(null);
            }

            // Persist changes
            $this->em->persist($outpass);

            // Flush and clear the EntityManager in batches
            if (($count % $batchSize) === 0) {
                $this->em->flush();

                // Detach only the current entity
                $this->em->detach($outpass);
            }
            $count++;
        }

        // Final flush
        $this->em->flush();
    }

    /**
     * Encrypt the QR data using AES-256-GCM
     */
    public function encryptQrData(string $data, string $sharedSecret): string
    {
        // Generate a key from the shared secret
        $key = hash('sha256', $sharedSecret, true);
        
        // Generate a random initialization vector (IV)
        $iv = random_bytes(12);

        // Generate a random tag
        $tag = null;
        
        // Encrypt the data using AES-256-GCM
        $cipherText = openssl_encrypt($data, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

        // Return the encrypted data as a base64-encoded string
        return base64_encode($iv . $tag . $cipherText);
    }

    public function createTemplate(Gender $gender, array $templateData, array $fields, bool $isSystemTemplate = false): void
    {
        $template = new OutpassTemplate();
        $template->setName($templateData['name']);
        $template->setDescription($templateData['description']);
        $template->setGender($gender);
        $template->setSystemTemplate($isSystemTemplate);
        $template->setAllowAttachments($templateData['allowAttachments']);
        $template->setActive(true);

        // Set fields collection if bidirectional relation is set up
        $fieldCollection = [];

        foreach ($fields as $fieldData) {
            $field = new OutpassTemplateField();
            $field->setTemplate($template);
            $field->setFieldName($fieldData['name']);
            $field->setFieldType($fieldData['type']);
            $field->setIsSystemField($fieldData['system'] ?? false);
            $field->setIsRequired($fieldData['required'] ?? false);

            $this->em->persist($field);
            $fieldCollection[] = $field;
        }

        $this->em->persist($template);
        $this->em->flush();
    }

    public function getTemplates(User $warden, ?string $passType): array|OutpassTemplate
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t', 'f')
            ->from(OutpassTemplate::class, 't')
            ->leftJoin('t.fields', 'f') // Assumes a OneToMany relation
            ->where('t.isActive = :active')
            ->andWhere('t.gender = :gender')
            ->setParameter('active', true)
            ->setParameter('gender', $warden->getGender()->value);

        // If $passType is set, filter by template name
        if ($passType !== null) {
            $normalized = ucwords(str_replace('_', ' ', $passType));
            $qb->andWhere('t.name = :name')
                ->setParameter('name', $normalized);
        }

        $results = $qb->getQuery()->getResult();

        // If no results found, return an empty array
        if (empty($results)) {
            return [];
        }

        return count($results) > 1 ? $results : $results[0];
    }
}
