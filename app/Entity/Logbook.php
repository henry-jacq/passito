<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'logbook')]
class Logbook
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $logId;

    #[ORM\ManyToOne(targetEntity: Verifier::class)]
    #[ORM\JoinColumn(name: 'verifier_id', referencedColumnName: 'id', nullable: false)]
    private Verifier $verifier;

    #[ORM\ManyToOne(targetEntity: OutpassRequest::class)]
    #[ORM\JoinColumn(name: 'outpass_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private OutpassRequest $outpass;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $inTime;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $outTime;

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
