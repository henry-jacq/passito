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

    public function getVerifier(int $id): Verifier|bool
    {
        try {
            return $this->em->getRepository(Verifier::class)->find($id);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getVerifiers(): array
    {
        return $this->em->getRepository(Verifier::class)->findAll();
    }

    public function generateAuthToken()
    {
        return bin2hex(random_bytes(16));
    }
}
