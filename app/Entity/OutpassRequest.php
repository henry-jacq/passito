<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\OutpassType;
use App\Enum\OutpassStatus;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_requests', indexes: [
    new ORM\Index(name: "pass_type_idx", columns: ["passType"]),
    new ORM\Index(name: "status_idx", columns: ["status"]),
    new ORM\Index(name: "created_at_idx", columns: ["created_at"])
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

    #[ORM\Column(type: 'string', enumType: OutpassType::class)]
    private OutpassType $passType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $destination;

    #[ORM\Column(type: 'string', length: 255)]
    private string $purpose;

    #[ORM\Column(type: 'string', enumType: OutpassStatus::class)]
    private OutpassStatus $status;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $attachments = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $remarks = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $document = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $qrCode = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'approved_by', referencedColumnName: 'id', nullable: true)]
    private ?User $approvedBy = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $approvedTime = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\OneToMany(mappedBy: 'outpassRequest', targetEntity: ParentVerification::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $parentVerifications;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->parentVerifications = new ArrayCollection();
    }
    
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

    public function getPassType(): OutpassType
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

    public function getStatus(): OutpassStatus
    {
        return $this->status;
    }

    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
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

    public function setPassType(OutpassType $passType): void
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

    public function setStatus(OutpassStatus $status): void
    {
        $this->status = $status;
    }

    public function setRemarks(?string $remarks): void
    {
        $this->remarks = $remarks;
    }

    public function setDocument(?string $document): void
    {
        $this->document = $document;
    }

    public function setQrCode(?string $qrCode): void
    {
        $this->qrCode = $qrCode;
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

    public function getParentVerifications(): Collection
    {
        return $this->parentVerifications;
    }

    public function addParentVerification(ParentVerification $verification): void
    {
        if (!$this->parentVerifications->contains($verification)) {
            $this->parentVerifications[] = $verification;
            $verification->setOutpassRequest($this);
        }
    }

    public function removeParentVerification(ParentVerification $verification): void
    {
        if ($this->parentVerifications->removeElement($verification)) {
            if ($verification->getOutpassRequest() === $this) {
                $verification->setOutpassRequest(null);
            }
        }
    }
}
