<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\OutpassStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_requests', indexes: [
    new ORM\Index(name: "pass_type_idx", columns: ["passType"])
])]
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

    #[ORM\Column(type: 'string', enumType: OutpassStatus::class)]
    private OutpassStatus $passType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $destination;

    #[ORM\Column(type: 'string', length: 255)]
    private string $purpose;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $attachments = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $remarks = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'approved_by', referencedColumnName: 'id', nullable: true)]
    private ?User $approvedBy = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $approvedTime = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    public function getToDate(): DateTime
    {
        return $this->toDate;
    }

    public function getFromTime(): DateTime
    {
        return $this->fromTime;
    }

    public function getToTime(): DateTime
    {
        return $this->toTime;
    }

    public function getPassType(): OutpassStatus
    {
        return $this->passType;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getPurpose(): string
    {
        return $this->purpose;
    }

    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function getApprovedTime(): ?DateTime
    {
        return $this->approvedTime;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setStudent(Student $student): void
    {
        $this->student = $student;
    }

    public function setFromDate(DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    public function setToDate(DateTime $toDate): void
    {
        $this->toDate = $toDate;
    }

    public function setFromTime(DateTime $fromTime): void
    {
        $this->fromTime = $fromTime;
    }

    public function setToTime(DateTime $toTime): void
    {
        $this->toTime = $toTime;
    }

    public function setPassType(OutpassStatus $passType): void
    {
        $this->passType = $passType;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }

    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
    }

    public function setAttachments(?array $attachments): void
    {
        $this->attachments = $attachments;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setRemarks(?string $remarks): void
    {
        $this->remarks = $remarks;
    }

    public function setApprovedBy(?User $approvedBy): void
    {
        $this->approvedBy = $approvedBy;
    }

    public function setApprovedTime(?DateTime $approvedTime): void
    {
        $this->approvedTime = $approvedTime;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
