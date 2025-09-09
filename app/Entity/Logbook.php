<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'logbook')]
class Logbook
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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $inTime;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $outTime;

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

    public function getInTime(): ?DateTime
    {
        return $this->inTime;
    }

    public function setInTime(?DateTime $inTime): void
    {
        $this->inTime = $inTime;
    }

    public function getOutTime(): ?DateTime
    {
        return $this->outTime;
    }

    public function setOutTime(?DateTime $outTime): void
    {
        $this->outTime = $outTime;
    }

    public function getLateDuration(): string
    {
        if ($this->inTime === null || $this->outTime === null) {
            return sprintf('N/A');
        }

        $outpass = $this->getOutpass();
        $outpassReturnTime = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $outpass->getToDate()->format('Y-m-d') . ' ' . $outpass->getToTime()->format('H:i:s')
        );

        $logReturnTime = $this->getInTime();
        $carbonInterval = Carbon::instance($outpassReturnTime)->diffForHumans(Carbon::instance($logReturnTime));

        return $carbonInterval;
    }
}
