<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Enum\Gender;
use App\Core\Storage;
use App\Enum\UserRole;
use App\Entity\Student;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use App\Dto\OutpassSettings;
use App\Entity\WardenAssignment;
use App\Enum\VerifierMode;
use App\Entity\OutpassTemplate;
use App\Entity\OutpassTemplateField;
use App\Services\SystemSettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class OutpassService
{
    public function __construct(
        private readonly Storage $storage,
        private readonly EntityManagerInterface $em,
        private readonly SystemSettingsService $settingsService
    )
    {
    }

    private const OUTPASS_SETTING_KEYS = [
        'parentApproval' => 'outpass_parent_approval',
        'weekdayCollegeHoursStart' => 'outpass_weekday_college_hours_start',
        'weekdayCollegeHoursEnd' => 'outpass_weekday_college_hours_end',
        'weekdayOvernightStart' => 'outpass_weekday_overnight_start',
        'weekdayOvernightEnd' => 'outpass_weekday_overnight_end',
        'weekendStartTime' => 'outpass_weekend_start_time',
        'weekendEndTime' => 'outpass_weekend_end_time',
        'lateArrivalGraceMinutes' => 'outpass_late_arrival_grace_minutes',
    ];
    private const VERIFIER_MODE_KEY = 'verifier_mode';

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

    public function getStudentOutpassStats(Student $student): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('o.status AS status', 'COUNT(o.id) AS count')
            ->from(OutpassRequest::class, 'o')
            ->where('o.student = :student')
            ->setParameter('student', $student)
            ->groupBy('o.status');

        $results = $qb->getQuery()->getArrayResult();

        $counts = [
            'total' => 0,
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
        ];

        foreach ($results as $row) {
            $status = $row['status']->value;
            $count = (int) $row['count'];
            $counts['total'] += $count;

            if ($status === 'approved' || $status === 'expired') {
                $counts['approved'] += $count;
            } elseif ($status === 'pending' || $status === 'parent_pending' || $status === 'parent_approved') {
                $counts['pending'] += $count;
            } elseif ($status === 'rejected' || $status === 'parent_denied') {
                $counts['rejected'] += $count;
            }
        }

        return $counts;
    }

    public function getOutpassHistoryByStudent(Student $student, int $page = 1, int $limit = 10): array
    {
        $page = max(1, $page);
        $limit = max(1, $limit);
        $offset = ($page - 1) * $limit;

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(OutpassRequest::class, 'o')
            ->where('o.student = :student')
            ->setParameter('student', $student)
            ->orderBy('o.createdAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        $paginator = new Paginator($query, true);
        $total = count($paginator);
        $totalPages = (int) ceil($total / $limit);

        return [
            'data' => iterator_to_array($paginator),
            'total' => $total,
            'currentPage' => $page,
            'totalPages' => max(1, $totalPages),
        ];
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

    public function getPendingOutpass(int $page = 1, int $limit = 10, bool $paginate = true, ?User $warden = null, ?string $hostelFilter = null, ?string $search = null, ?string $date = null)
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

            if (!empty($search)) {
                $isNumeric = ctype_digit($search);
                if ($isNumeric) {
                    $queryBuilder->andWhere('s.digitalId = :digitalId')
                        ->setParameter('digitalId', (int) $search);
                } else {
                    $queryBuilder->andWhere('u.name LIKE :search')
                        ->setParameter('search', '%' . $search . '%');
                }
            }

            if (!empty($date)) {
                $start = \DateTime::createFromFormat('Y-m-d', $date);
                if ($start) {
                    $end = (clone $start)->setTime(23, 59, 59);
                    $start->setTime(0, 0, 0);
                    $queryBuilder->andWhere('o.createdAt BETWEEN :startDate AND :endDate')
                        ->setParameter('startDate', $start)
                        ->setParameter('endDate', $end);
                }
            }

            // Optional hostel filtering for wardens
            if (UserRole::isAdmin($warden->getRole()->value)) {
                $ids = $this->getAssignedHostelIds($warden);

                if (!empty($ids)) {
                    if ($hostelFilter && $hostelFilter !== 'default') {
                        $hostelId = (int) $hostelFilter;
                        if (in_array($hostelId, $ids, true)) {
                            $queryBuilder->andWhere('h.id = :id')
                                ->setParameter('id', $hostelId);
                        } else {
                            $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                                ->setParameter('ids', $ids);
                        }
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                            ->setParameter('ids', $ids);
                    }
                } elseif ($hostelFilter && $hostelFilter !== 'default') {
                    $queryBuilder->andWhere('h.id = :id')
                        ->setParameter('id', (int) $hostelFilter);
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

        if (!empty($search)) {
            $isNumeric = ctype_digit($search);
            if ($isNumeric) {
                $queryBuilder->andWhere('s.digitalId = :digitalId')
                    ->setParameter('digitalId', (int) $search);
            } else {
                $queryBuilder->andWhere('u.name LIKE :search')
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        if (!empty($date)) {
            $start = \DateTime::createFromFormat('Y-m-d', $date);
            if ($start) {
                $end = (clone $start)->setTime(23, 59, 59);
                $start->setTime(0, 0, 0);
                $queryBuilder->andWhere('o.createdAt BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', $start)
                    ->setParameter('endDate', $end);
            }
        }

        if (UserRole::isAdmin($warden->getRole()->value)) {
            $ids = $this->getAssignedHostelIds($warden);

            if (!empty($ids)) {
                if ($hostelFilter && $hostelFilter !== 'default') {
                    $hostelId = (int) $hostelFilter;
                    if (in_array($hostelId, $ids, true)) {
                        $queryBuilder->andWhere('h.id = :id')
                            ->setParameter('id', $hostelId);
                    } else {
                        $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                            ->setParameter('ids', $ids);
                    }
                } else {
                    $queryBuilder->andWhere($queryBuilder->expr()->in('h.id', ':ids'))
                        ->setParameter('ids', $ids);
                }
            } elseif ($hostelFilter && $hostelFilter !== 'default') {
                $queryBuilder->andWhere('h.id = :id')
                    ->setParameter('id', (int) $hostelFilter);
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

    private function getAssignedHostelIds(User $warden): array
    {
        $assignments = $this->em->getRepository(WardenAssignment::class)->findBy([
            'assignedTo' => $warden
        ]);

        $ids = [];
        foreach ($assignments as $assignment) {
            $ids[] = $assignment->getHostelId();
        }

        return array_values(array_unique($ids));
    }

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
            OutpassStatus::REJECTED->value,
            OutpassStatus::PARENT_DENIED->value,
        ];

        $wardenGender = $warden->getGender()->value;

        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(OutpassRequest::class, 'o')
            ->join('o.student', 's')
            ->join('s.user', 'u')
            ->join('o.template', 't')
            ->where($queryBuilder->expr()->in('o.status', ':statuses'))
            ->andWhere('u.gender = :gender')
            ->orderBy('o.createdAt', 'DESC')
            ->setParameter('statuses', $statuses)
            ->setParameter('gender', $wardenGender);

        return $queryBuilder;
    }

    public function getOutpassRecords(int $page = 1, int $limit = 10, ?User $warden = null, ?string $search = null, ?string $filter = null, ?string $date = null)
    {
        $offset = ($page - 1) * $limit;

        $queryBuilder = $this->buildBaseOutpassQuery($warden);

        if (!empty($search)) {
            $isNumeric = ctype_digit($search);
            if ($isNumeric) {
                $queryBuilder->andWhere('s.digitalId = :digitalId')
                    ->setParameter('digitalId', (int) $search);
            } else {
                $queryBuilder->andWhere('u.name LIKE :search')
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        if (!empty($filter)) {
            $status = OutpassStatus::tryFrom(strtolower($filter));
            if ($status) {
                $queryBuilder->andWhere('o.status = :status')
                    ->setParameter('status', $status->value);
            }
        }

        if (!empty($date)) {
            $start = \DateTime::createFromFormat('Y-m-d', $date);
            if ($start) {
                $end = (clone $start)->setTime(23, 59, 59);
                $start->setTime(0, 0, 0);
                $queryBuilder->andWhere('o.createdAt BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', $start)
                    ->setParameter('endDate', $end);
            }
        }

        $queryBuilder
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
                    'course' => $o->getStudent()->getProgram()->getProgramName(),
                    'branch' => $o->getStudent()->getProgram()->getShortCode(),
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


    public function getSettings(Gender $gender): OutpassSettings
    {
        $genderKey = $gender->value;
        $defaults = $this->getDefaultValues();
        $merged = [];

        foreach (self::OUTPASS_SETTING_KEYS as $field => $key) {
            $defaultValue = $defaults[$genderKey][$field] ?? null;
            $perGender = $this->settingsService->get($key, [
                'male' => $defaults['male'][$field] ?? null,
                'female' => $defaults['female'][$field] ?? null,
            ]);
            if (!is_array($perGender)) {
                $perGender = [
                    'male' => $defaults['male'][$field] ?? null,
                    'female' => $defaults['female'][$field] ?? null,
                ];
            }
            $merged[$field] = $perGender[$genderKey] ?? $defaultValue;
        }

        return new OutpassSettings($gender, $merged);
    }

    public function getVerifierMode(): VerifierMode
    {
        $mode = $this->settingsService->get(self::VERIFIER_MODE_KEY, null);
        if (is_string($mode)) {
            return VerifierMode::tryFrom($mode) ?? VerifierMode::MANUAL;
        }

        return VerifierMode::MANUAL;
    }

    public function updateSettings(User $user, array $data): OutpassSettings
    {
        $defaults = $this->getDefaultValues();
        $genderKey = $user->getGender()->value;
        $current = $defaults[$genderKey] ?? [];

        // Check if "reset_defaults" is set to "true"
        if (!empty($data['reset_defaults']) && $data['reset_defaults'] === 'true') {
            $current = $defaults[$genderKey] ?? [];
            $this->settingsService->set(self::VERIFIER_MODE_KEY, VerifierMode::MANUAL->value);
        } else {
            $normalizeTime = function (?string $timeString): ?string {
                $timeString = $timeString !== null ? trim($timeString) : '';
                if ($timeString === '') {
                    return null;
                }
                $parsed = \DateTime::createFromFormat('H:i', $timeString);
                if ($parsed) {
                    return $parsed->format('H:i');
                }
                $parsedWithSeconds = \DateTime::createFromFormat('H:i:s', $timeString);
                return $parsedWithSeconds ? $parsedWithSeconds->format('H:i') : null;
            };

            $current['weekdayCollegeHoursStart'] = $normalizeTime($data['weekday_college_hours_start'] ?? null);
            $current['weekdayCollegeHoursEnd'] = $normalizeTime($data['weekday_college_hours_end'] ?? null);
            $current['weekdayOvernightStart'] = $normalizeTime($data['weekday_overnight_start'] ?? null);
            $current['weekdayOvernightEnd'] = $normalizeTime($data['weekday_overnight_end'] ?? null);
            $current['weekendStartTime'] = $normalizeTime($data['weekend_start_time'] ?? null);
            $current['weekendEndTime'] = $normalizeTime($data['weekend_end_time'] ?? null);
            $current['parentApproval'] = !empty($data['parent_approval']);
            $lateArrivalGrace = isset($data['late_arrival_grace_minutes']) ? (int) $data['late_arrival_grace_minutes'] : 30;
            $current['lateArrivalGraceMinutes'] = max(0, $lateArrivalGrace);

            if (isset($data['verifier_mode'])) {
                $verifierMode = VerifierMode::tryFrom($data['verifier_mode']) ?? VerifierMode::MANUAL;
                $this->settingsService->set(self::VERIFIER_MODE_KEY, $verifierMode->value);
            }
        }

        foreach (self::OUTPASS_SETTING_KEYS as $field => $key) {
            $perGender = $this->settingsService->get($key, [
                'male' => $defaults['male'][$field] ?? null,
                'female' => $defaults['female'][$field] ?? null,
            ]);
            if (!is_array($perGender)) {
                $perGender = [
                    'male' => $defaults['male'][$field] ?? null,
                    'female' => $defaults['female'][$field] ?? null,
                ];
            }

            if (!array_key_exists($genderKey, $perGender)) {
                $perGender[$genderKey] = $defaults[$genderKey][$field] ?? null;
            }

            $perGender[$genderKey] = $current[$field] ?? ($defaults[$genderKey][$field] ?? null);
            $this->settingsService->set($key, $perGender);
        }

        return new OutpassSettings($user->getGender(), $current);
    }

    private function getDefaultValues(): array
    {
        return [
            'male' => [
                'weekdayCollegeHoursStart' => '09:00',
                'weekdayCollegeHoursEnd' => '17:00',
                'weekdayOvernightStart' => '22:00',
                'weekdayOvernightEnd' => '06:00',
                'weekendStartTime' => '09:00',
                'weekendEndTime' => '23:59',
                'parentApproval' => false,
                'lateArrivalGraceMinutes' => 30,
            ],
            'female' => [
                'weekdayCollegeHoursStart' => '09:00',
                'weekdayCollegeHoursEnd' => '17:00',
                'weekdayOvernightStart' => '20:00',
                'weekdayOvernightEnd' => '06:00',
                'weekendStartTime' => '09:00',
                'weekendEndTime' => '22:00',
                'parentApproval' => true,
                'lateArrivalGraceMinutes' => 30,
            ],
        ];
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
     * Remove documents and attachments for expired outpasses.
     */
    public function removeExpireOutpassFiles(int $batchSize = 20): void
    {
        // Periodically remove documents for outpasses already marked as expired.
        $outpasses = $this->em->getRepository(OutpassRequest::class)
        ->createQueryBuilder('o')
        ->where('o.status IN (:status)')
        ->setParameter('status', [OutpassStatus::EXPIRED->value])
        ->getQuery()
        ->getResult();

        $count = 0;

        foreach ($outpasses as $outpass) {
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

    /**
     * Decrypt the QR data using AES-256-GCM
     */
    public function decryptQrData(string $payload, string $sharedSecret): ?string
    {
        $raw = base64_decode($payload, true);
        if ($raw === false || strlen($raw) < 28) {
            return null;
        }

        $key = hash('sha256', $sharedSecret, true);
        $iv = substr($raw, 0, 12);
        $tag = substr($raw, 12, 16);
        $cipherText = substr($raw, 28);

        $plain = openssl_decrypt($cipherText, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return $plain !== false ? $plain : null;
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
