<?php

namespace App\Services;

use DateTime;
use App\Entity\User;
use App\Core\Session;
use App\Entity\Verifier;
use App\Entity\VerifierLog;
use App\Enum\OutpassStatus;
use App\Enum\VerifierStatus;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

// TODO: Generate outpass report
// TODO: Generate detailed analytics report

class VerifierService
{
    public function __construct(
        private readonly Session $session,
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
            $verifier->setName($name);
            $verifier->setLocation($location);
            $verifier->setCreatedAt(new DateTime());
            $verifier->setStatus(VerifierStatus::PENDING);
            $verifier->setAuthToken($this->generateAuthToken());

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
        $verifier = $this->em->getRepository(Verifier::class)->findOneBy(['authToken' => $authToken, 'machineId' => $machineId]);
        return $verifier && $verifier->getStatus() === VerifierStatus::ACTIVE;
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
            $verifier->setStatus(VerifierStatus::INACTIVE);

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
        $verifier->setStatus(VerifierStatus::ACTIVE);
        $this->em->persist($verifier);
        $this->em->flush();
        return $verifier;
    }

    /**
     * Deactive verifier to restrict access to API
     */
    public function deactivate(int $verifier_id)
    {
        $verifier = $this->getVerifier($verifier_id);
        $verifier->setStatus(VerifierStatus::INACTIVE);
        $this->em->persist($verifier);
        $this->em->flush();
        return $verifier;
    }

    /**
     * Sync data
     */
    public function syncData(string $authToken, array $data): bool
    {
        $verifier = $this->em->getRepository(Verifier::class)->findOneBy(['authToken' => $authToken]);
        if ($verifier) {
            if ($this->logExistsByOutpassId($data['id'])) {
                $log = $this->em->getRepository(VerifierLog::class)->findOneBy(['outpass' => $data['id']]);
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
        $logs = new VerifierLog();
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
        return $this->em->getRepository(VerifierLog::class)->findOneBy(['outpass' => $outpass_id]) !== null;
    }

    /**
     * Update log
     */
    public function updateLog(VerifierLog $log): VerifierLog
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
            return $this->em->getRepository(VerifierLog::class)->findBy(['verifier' => $verifier]);
        }
        return [];
    }

    /**
     * Fetch Gender-wise logs with or without pagination
     */
    public function fetchLogsByGender(User $user, int $page = 1, int $limit = 10, bool $paginate = true): array
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder->select('log')
            ->from(VerifierLog::class, 'log')
            ->join('log.outpass', 'outpass')
            ->join('outpass.student', 'student')
            ->join('student.user', 'user')
            ->where('user.gender = :gender')
            ->orderBy('log.outTime', 'DESC')
            ->setParameter('gender', $user->getGender()->value);

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
    public function fetchCheckedOutLogs(User $user): array
    {
        $allLogs = $this->fetchLogsByGender(user: $user, paginate: false);
        $userGender = $user->getGender()?->value;

        return array_filter($allLogs, function ($log) use ($userGender) {
            if ($log->getInTime() !== null) {
                return false;
            }

            $logGender = $log->getOutpass()?->getStudent()?->getUser()?->getGender()?->value;

            return $logGender === $userGender;
        });
    }
}
