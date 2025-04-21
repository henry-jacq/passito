<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'parent_verifications')]
class ParentVerification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: OutpassRequest::class)]
    #[ORM\JoinColumn(name: 'outpass_id', nullable: false)]
    private OutpassRequest $outpassRequest;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $verificationToken;

    #[ORM\Column(type: 'boolean')]
    private bool $isUsed = false;

    #[ORM\Column(type: 'string', enumType: OutpassStatus::class, nullable: true)]
    private ?OutpassStatus $decision = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $verifiedAt = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOutpassRequest(): OutpassRequest
    {
        return $this->outpassRequest;
    }

    public function setOutpassRequest(?OutpassRequest $outpassRequest): void
    {
        $this->outpassRequest = $outpassRequest;
    }

    public function getVerificationToken(): string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(string $verificationToken): void
    {
        $this->verificationToken = $verificationToken;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }

    public function getDecision(): ?OutpassStatus
    {
        return $this->decision;
    }

    public function setDecision(OutpassStatus $decision): void
    {
        $this->decision = $decision;
    }

    public function getVerifiedAt(): ?DateTime
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(DateTime $verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
