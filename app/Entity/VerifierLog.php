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
}
