<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "jobs")]
class Job
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $type;

    #[ORM\Column(type: "json")]
    private array $payload;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $availableAt;

    #[ORM\Column(type: "integer")]
    private int $attempts = 0;

    #[ORM\Column(type: "integer")]
    private int $maxAttempts = 3;

    #[ORM\Column(type: "string", length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $lastError = null;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $createdAt;

    public function __construct(string $type, array $payload, int $delay = 0, int $maxAttempts = 3)
    {
        $this->type = $type;
        $this->payload = $payload;
        $this->availableAt = new \DateTime("+{$delay} seconds");
        $this->maxAttempts = $maxAttempts;
        $this->status = 'pending';
        $this->createdAt = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getAvailableAt(): \DateTime
    {
        return $this->availableAt;
    }

    public function setAvailableAt(\DateTimeInterface $availableAt): void
    {
        $this->availableAt = $availableAt;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function incrementAttempts(): void
    {
        $this->attempts++;
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }
    
    public function setLastError(?string $error): void
    {
        $this->lastError = $error;
    }
}
