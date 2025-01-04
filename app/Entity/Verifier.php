<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\VerifierStatus;
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

    #[ORM\Column(type: 'string', enumType: VerifierStatus::class)]
    private VerifierStatus $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ipAddress;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $machineId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $authToken;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $lastSync;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->verifierName;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getStatus(): VerifierStatus
    {
        return $this->status;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress ?? null;
    }

    public function getMachineId(): ?string
    {
        return $this->machineId ?? null;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getLastSync(): ?DateTime
    {
        return $this->lastSync ?? null;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setName(string $verifierName): void
    {
        $this->verifierName = $verifierName;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setStatus(VerifierStatus $status): void
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
