<?php

namespace App\Services;

use DateTime;
use App\Core\Session;
use App\Entity\Verifier;
use App\Enum\VerifierStatus;
use Doctrine\ORM\EntityManagerInterface;

// TODO: Generate outpass report
// TODO: Generate detailed analytics report

class VerifierService
{
    public function __construct(
        private readonly Session $session,
        private readonly EntityManagerInterface $em
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
     * Check if machine is valid
     */    
    public function isValidMachine(string $machineId): bool
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
}
