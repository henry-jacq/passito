<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'verifiers')]
class Verifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $verifierName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $location;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255)]
    private string $ipAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private string $machineId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $authToken;

    #[ORM\Column(type: 'datetime')]
    private DateTime $lastSync;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getVerifierName(): string
    {
        return $this->verifierName;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function getMachineId(): string
    {
        return $this->machineId;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getLastSync(): DateTime
    {
        return $this->lastSync;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setVerifierName(string $verifierName): void
    {
        $this->verifierName = $verifierName;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function setMachineId(string $machineId): void
    {
        $this->machineId = $machineId;
    }

    public function setAuthToken(string $authToken): void
    {
        $this->authToken = $authToken;
    }

    public function setLastSync(DateTime $lastSync): void
    {
        $this->lastSync = $lastSync;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
