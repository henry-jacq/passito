<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'verifier_logs')]
class VerifierLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $logId;

    #[ORM\ManyToOne(targetEntity: Verifier::class)]
    #[ORM\JoinColumn(name: 'verifier_id', referencedColumnName: 'id', nullable: false)]
    private Verifier $verifier;

    #[ORM\ManyToOne(targetEntity: OutpassRequest::class)]
    #[ORM\JoinColumn(name: 'outpass_id', referencedColumnName: 'id', nullable: false)]
    private OutpassRequest $outpass;

    #[ORM\Column(type: 'datetime')]
    private DateTime $inTime;

    #[ORM\Column(type: 'datetime')]
    private DateTime $outTime;

    #[ORM\Column(type: 'datetime')]
    private DateTime $timestamp;

    public function getLogId(): int
    {
        return $this->logId;
    }

    public function getVerifier(): Verifier
    {
        return $this->verifier;
    }

    public function setVerifier(Verifier $verifier): void
    {
        $this->verifier = $verifier;
    }

    public function getOutpass(): OutpassRequest
    {
        return $this->outpass;
    }

    public function setOutpass(OutpassRequest $outpass): void
    {
        $this->outpass = $outpass;
    }

    public function getInTime(): DateTime
    {
        return $this->inTime;
    }

    public function setInTime(DateTime $inTime): void
    {
        $this->inTime = $inTime;
    }

    public function getOutTime(): DateTime
    {
        return $this->outTime;
    }

    public function setOutTime(DateTime $outTime): void
    {
        $this->outTime = $outTime;
    }

    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTime $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}

