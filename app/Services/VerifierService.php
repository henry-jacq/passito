<?php

namespace App\Services;

use DateTime;
use App\Core\Session;
use App\Entity\Verifier;
use App\Entity\VerifierLog;
use App\Enum\OutpassStatus;
use App\Enum\VerifierStatus;
use Doctrine\ORM\EntityManagerInterface;

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
            if (!$this->logExistsByOutpassId($data['id'])) {
                if (!$this->createLog($verifier, $data)) {
                    // Log creation failed because outpass does not exist
                    return false;
                }
            } else {
                $log = $this->em->getRepository(VerifierLog::class)->findOneBy(['outpass' => $data['id']]);
                $this->updateLog($log);
            }

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
        $this->em->persist($log);
        $this->em->persist($outpass);
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
     * Fetch all logs
     */
    public function fetchAllLogs(): array
    {
        return $this->em->getRepository(VerifierLog::class)->findAll();
    }

    /**
     * Fetch checked-out logs
     */
    public function fetchCheckedOutLogs(): array
    {
        return $this->em->getRepository(VerifierLog::class)->findBy(['inTime' => NULL]);
    }
}
