<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Entity\Verifier;
use App\Entity\Logbook;
use App\Enum\OutpassStatus;
use App\Enum\VerifierMode;
use App\Enum\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class VerifierService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService
    )
    {
    }
    
    /**
     * Create verifier
     */
    public function createVerifier(string $name, string $location): Verifier|bool
    {
        try {
            $verifier = new Verifier();
            $verifier->setVerifierName($name);
            $verifier->setLocation($location);
            $verifier->setCreatedAt(new DateTime());
            $verifier->setAuthToken($this->generateAuthToken());
            $verifier->setType(VerifierMode::AUTOMATED);

            $this->em->persist($verifier);
            $this->em->flush();

            return $verifier;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get verifier by id
     */
    public function getVerifier(int $id): Verifier|bool
    {
        try {
            return $this->em->getRepository(Verifier::class)->find($id);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update the verifier last sync time
     */
    public function updateLastSync(Verifier $verifier)
    {
        $verifier->setLastSync(new \DateTime());
        $this->em->persist($verifier);
        $this->em->flush();
    }

    /**
     * Get all verifiers
     */
    public function getVerifiers(): array
    {
        return $this->em->getRepository(Verifier::class)->findAll();
    }

    public function createManualVerifier(User $user, string $location): Verifier|bool
    {
        try {
            $verifier = new Verifier();
            $verifier->setVerifierName($user->getName());
            $verifier->setLocation($location);
            $verifier->setCreatedAt(new DateTime());
            $verifier->setAuthToken($this->generateAuthToken());
            $verifier->setType(VerifierMode::MANUAL);
            $verifier->setUser($user);

            $this->em->persist($verifier);
            $this->em->flush();

            return $verifier;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getVerifiersByType(VerifierMode $type): array
    {
        return $this->em->getRepository(Verifier::class)->findBy(['type' => $type]);
    }

    public function getVerifierByUser(User $user): ?Verifier
    {
        return $this->em->getRepository(Verifier::class)->findOneBy([
            'user' => $user,
            'type' => VerifierMode::MANUAL,
        ]);
    }

    public function isActiveVerifier(Verifier $verifier): bool
    {
        if ($verifier->getType() === VerifierMode::MANUAL) {
            $user = $verifier->getUser();
            return $user && $user->getStatus() === UserStatus::ACTIVE;
        }

        return $verifier->getMachineId() !== null;
    }

    /**
     * Generate auth token
     */
    public function generateAuthToken()
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Check if machine ID is valid
     */    
    public function isValidMachineId(string $machineId): bool
    {
        return $this->em->getRepository(Verifier::class)->findOneBy(['machineId' => $machineId]) !== null;
    }

    /**
     * Check if token is valid
     */
    public function isValidToken(string $authToken): bool
    {
        return $this->em->getRepository(Verifier::class)->findOneBy(['authToken' => $authToken]) !== null;
    }

    /**
     * Check if verifier is active
     */
    public function isActive(string $authToken, string $machineId): bool
    {
        $verifier = $this->em->getRepository(Verifier::class)->findOneBy([
            'authToken' => $authToken,
            'machineId' => $machineId,
        ]);
        return (bool) $verifier;
    }

    /**
     * Register verifier
     */
    public function register(string $machine_id, string $host, string $authToken): bool
    {
        $verifier = $this->em->getRepository(Verifier::class)->findOneBy(['authToken' => $authToken]);

        if ($verifier) {
            $verifier->setMachineId($machine_id);
            $verifier->setIpAddress($host);

            $this->em->persist($verifier);
            $this->em->flush();

            return true;
        }

        return false;
    }

    /**
     * Delete verifier
     */
    public function deleteVerifier(int $verifier_id)
    {
        $verifier = $this->getVerifier($verifier_id);
        if ($verifier) {
            $this->em->remove($verifier);
            $this->em->flush();
            return true;
        }
        return false;
    }

    /**
     * Activate verifier to access API
     */
    public function activate(int $verifier_id)
    {
        $verifier = $this->getVerifier($verifier_id);
        if (!$verifier instanceof Verifier) {
            return false;
        }
        if ($verifier->getType() === VerifierMode::MANUAL) {
            $user = $verifier->getUser();
            if ($user) {
                $user->setStatus(UserStatus::ACTIVE);
                $this->em->flush();
                return $verifier;
            }
            return false;
        }
        if ($verifier->getMachineId() === null) {
            return false;
        }
        return $verifier;
    }

    /**
     * Deactive verifier to restrict access to API
     */
    public function deactivate(int $verifier_id)
    {
        $verifier = $this->getVerifier($verifier_id);
        if ($verifier instanceof Verifier) {
            if ($verifier->getType() === VerifierMode::MANUAL) {
                $user = $verifier->getUser();
                if ($user) {
                    $user->setStatus(UserStatus::INACTIVE);
                    $this->em->flush();
                }
            } else {
                $verifier->setMachineId(null);
                $verifier->setIpAddress(null);
                $verifier->setLastSync(new DateTime());
                $this->em->persist($verifier);
                $this->em->flush();
            }
        }
        return $verifier;
    }

    public function deleteManualVerifier(int $verifier_id): bool
    {
        $verifier = $this->getVerifier($verifier_id);
        if (!$verifier) {
            return false;
        }

        $user = $verifier->getUser();
        $this->em->remove($verifier);
        if ($user) {
            $this->em->remove($user);
        }
        $this->em->flush();
        return true;
    }

    /**
     * Sync data
     */
    public function syncData(string $authToken, array $data): bool
    {
        $verifier = $this->em->getRepository(Verifier::class)->findOneBy(['authToken' => $authToken]);
        if ($verifier) {
            if ($this->logExistsByOutpassId($data['id'])) {
                $log = $this->em->getRepository(Logbook::class)->findOneBy(['outpass' => $data['id']]);
                $this->updateLog($log);
            } else {
                if (!$this->createLog($verifier, $data)) {
                    // Log creation failed because outpass does not exist
                    return false;
                }
            }
            
            $this->updateLastSync($verifier);
            return true;
        }

        return false;
    }

    // create a log entry
    public function createLog(Verifier $verifier, array $data): bool
    {
        $logs = new Logbook();
        $outpass = $this->outpassService->getOutpassById($data['id']);

        if (!$outpass) {
            return false;
        }
        
        $logs->setVerifier($verifier);
        $logs->setOutpass($outpass);
        $logs->setOutTime(new DateTime());
        $this->em->persist($logs);
        $this->em->flush();
        return true;
    }

    public function logExistsByOutpassId(int $outpass_id): bool
    {
        return $this->em->getRepository(Logbook::class)->findOneBy(['outpass' => $outpass_id]) !== null;
    }

    /**
     * Update log
     */
    public function updateLog(Logbook $log): Logbook
    {
        $outpass = $log->getOutpass();

        $log->setInTime(new DateTime());
        $outpass->setStatus(OutpassStatus::EXPIRED);

        // Remove the attachments
        if (!empty($outpass->getAttachments())) {
            $this->outpassService->removeAttachments($outpass);
            $outpass->setAttachments([]);
        }

        // Remove the document and update the outpass record
        if (!empty($outpass->getDocument())) {
            $this->outpassService->removeOutpassDocument($outpass);
            $outpass->setDocument(null);
        }

        if (!empty($outpass->getQrCode())) {
            $this->outpassService->removeQrCode($outpass);
            $outpass->setQrCode(null);
        }
        
        $this->em->persist($outpass);        
        $this->em->persist($log);
        $this->em->flush();

        return $log;
    }

    /**
     * Get verifier logs
     */
    public function getLogs(int $verifier_id): array
    {
        $verifier = $this->getVerifier($verifier_id);
        if ($verifier) {
            return $this->em->getRepository(Logbook::class)->findBy(['verifier' => $verifier]);
        }
        return [];
    }

    /**
     * Fetch Gender-wise logs with or without pagination
     */
    public function fetchLogsByGender(User $user, int $page = 1, int $limit = 10, bool $paginate = true, ?string $search = null, ?string $date = null, ?string $action = null): array
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('log')
            ->from(Logbook::class, 'log')
            ->join('log.outpass', 'outpass')
            ->join('outpass.student', 'student')
            ->join('student.user', 'user')
            ->join('log.verifier', 'verifier')
            ->where('user.gender = :gender')
            ->orderBy('log.outTime', 'DESC')
            ->setParameter('gender', $user->getGender()->value);

        if (!empty($search)) {
            $isNumeric = ctype_digit($search);
            if ($isNumeric) {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->eq('student.rollNo', ':rollNo')
                )
                    ->setParameter('rollNo', (int) $search);
            } else {
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->like('user.name', ':search')
                )
                    ->setParameter('search', '%' . $search . '%');
            }
        }

        if (!empty($action)) {
            if ($action === 'checkout') {
                $queryBuilder->andWhere('log.inTime IS NULL');
            } elseif ($action === 'checkin') {
                $queryBuilder->andWhere('log.inTime IS NOT NULL');
            }
        }

        if (!empty($date)) {
            $start = \DateTime::createFromFormat('Y-m-d', $date);
            if ($start) {
                $end = (clone $start)->setTime(23, 59, 59);
                $start->setTime(0, 0, 0);

                if ($action === 'checkin') {
                    $queryBuilder->andWhere('log.inTime BETWEEN :start AND :end');
                } else {
                    $queryBuilder->andWhere('log.outTime BETWEEN :start AND :end');
                }
                $queryBuilder->setParameter('start', $start);
                $queryBuilder->setParameter('end', $end);
            }
        }

        if (!$paginate) {
            return $queryBuilder->getQuery()->getResult();
        }

        $offset = ($page - 1) * $limit;

        $queryBuilder->setFirstResult($offset)
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

    /**
     * Fetch checked-out logs
     */
    public function fetchCheckedOutLogs(User $user, ?\DateTimeInterface $forDate = null): array
    {
        $allLogs = $this->fetchLogsByGender(user: $user, paginate: false);
        $userGender = $user->getGender()?->value;
        $targetDate = $forDate?->format('Y-m-d');

        return array_filter($allLogs, function ($log) use ($userGender, $targetDate) {
            if ($log->getInTime() !== null) {
                return false;
            }

            if ($targetDate !== null) {
                $outTime = $log->getOutTime();
                if ($outTime === null || $outTime->format('Y-m-d') !== $targetDate) {
                    return false;
                }
            }

            $logGender = $log->getOutpass()?->getStudent()?->getUser()?->getGender()?->value;

            return $logGender === $userGender;
        });
    }

    /**
     * Fetch checked-in logs
     */
    public function fetchCheckedInLogs(User $user, ?\DateTimeInterface $forDate = null): array
    {
        $allLogs = $this->fetchLogsByGender(user: $user, paginate: false);
        $userGender = $user->getGender()?->value;
        $targetDate = $forDate?->format('Y-m-d');

        return array_filter($allLogs, function ($log) use ($userGender, $targetDate) {
            $inTime = $log->getInTime();
            if ($inTime === null) {
                return false;
            }

            if ($targetDate !== null && $inTime->format('Y-m-d') !== $targetDate) {
                return false;
            }

            $logGender = $log->getOutpass()?->getStudent()?->getUser()?->getGender()?->value;

            return $logGender === $userGender;
        });
    }

    /**
     * Fetch late arrivals beyond the configured grace period.
     * Fetch all late arrivals for a given user.
     */
    public function fetchLateArrivals(User $user, ?\DateTimeInterface $forDate = null): array
    {
        $allLogs = $this->fetchLogsByGender(user: $user, paginate: false);
        $userGender = $user->getGender()?->value;
        $targetDate = $forDate?->format('Y-m-d');
        $lateArrivals = [];
        $settings = $this->outpassService->getSettings($user->getGender());
        $graceMinutes = $settings?->getLateArrivalGraceMinutes() ?? 30;

        foreach ($allLogs as $log) {
            $outpass = $log->getOutpass();
            $actualInTime = $log->getInTime();

            if (!$outpass || $actualInTime === null) {
                continue;
            }

            if ($targetDate !== null && $actualInTime->format('Y-m-d') !== $targetDate) {
                continue;
            }

            $expectedReturnDate = $outpass->getToDate();
            $expectedReturnTime = $outpass->getToTime();

            if ($expectedReturnDate && $expectedReturnTime) {
                // Merge expected return date + time into one DateTime
                $expectedReturn = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $expectedReturnDate->format('Y-m-d') . ' ' . $expectedReturnTime->format('H:i:s')
                );

                if ($expectedReturn) {
                    $diff = $expectedReturn->diff($actualInTime);
                    $minutesLate = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

                    if ($actualInTime > $expectedReturn && $minutesLate > $graceMinutes) {
                        $logGender = $outpass->getStudent()?->getUser()?->getGender()?->value;
                        if ($logGender === $userGender) {
                            $lateArrivals[] = $log;
                        }
                    }
                }
            }
        }

        return $lateArrivals;
    }
}
