<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_requests')]
class OutpassRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id', nullable: false)]
    private Student $student;

    #[ORM\Column(type: 'date')]
    private DateTime $fromDate;

    #[ORM\Column(type: 'date')]
    private DateTime $toDate;

    #[ORM\Column(type: 'time')]
    private DateTime $fromTime;

    #[ORM\Column(type: 'time')]
    private DateTime $toTime;

    #[ORM\Column(type: 'string', length: 255)]
    private string $passType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $destination;

    #[ORM\Column(type: 'string', length: 255)]
    private string $subject;

    #[ORM\Column(type: 'string', length: 255)]
    private string $purpose;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $attachments = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $remarks = null;

    #[ORM\ManyToOne(targetEntity: Warden::class)]
    #[ORM\JoinColumn(name: 'approved_by', referencedColumnName: 'id', nullable: true)]
    private ?Warden $approvedBy = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $approvedTime = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;
}
