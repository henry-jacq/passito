<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;

#[ORM\Entity]
#[ORM\Table(name: 'outpasses')]
class Outpass
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private Student $student;

    #[ORM\Column(type: 'date')]
    private DateTime $fromDate;

    #[ORM\Column(type: 'date')]
    private DateTime $toDate;

    #[ORM\Column(type: 'time')]
    private DateTime $fromTime;

    #[ORM\Column(type: 'time')]
    private DateTime $toTime;

    #[ORM\Column(type: 'string', length: 64)]
    private string $passType;

    #[ORM\Column(type: 'string', length: 128)]
    private string $destination;

    #[ORM\Column(type: 'string', length: 256)]
    private string $subject;

    #[ORM\Column(type: 'text')]
    private string $purpose;

    #[ORM\Column(type: 'json', nullable: true)]
    private array $attachments = [];

    #[ORM\Column(type: 'string', length: 20)]
    private string $status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $wardenApprovalTime = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $wardenRemarks = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->createdAt = new DateTime();
    }
    
    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    public function setFromDate(DateTime $fromDate): self
    {
        $this->fromDate = $fromDate;
        return $this;
    }

    public function getToDate(): DateTime
    {
        return $this->toDate;
    }

    public function setToDate(DateTime $toDate): self
    {
        $this->toDate = $toDate;
        return $this;
    }

    public function getFromTime(): DateTime
    {
        return $this->fromTime;
    }

    public function setFromTime(DateTime $fromTime): self
    {
        $this->fromTime = $fromTime;
        return $this;
    }

    public function getToTime(): DateTime
    {
        return $this->toTime;
    }

    public function setToTime(DateTime $toTime): self
    {
        $this->toTime = $toTime;
        return $this;
    }

    public function getPassType(): string
    {
        return $this->passType;
    }

    public function setPassType(string $passType): self
    {
        $this->passType = $passType;
        return $this;
    }

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getPurpose(): string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;
        return $this;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function setAttachments(array $attachments): self
    {
        $this->attachments = $attachments;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getWardenApprovalTime(): ?DateTime
    {
        return $this->wardenApprovalTime;
    }

    public function setWardenApprovalTime(?DateTime $wardenApprovalTime): self
    {
        $this->wardenApprovalTime = $wardenApprovalTime;
        return $this;
    }

    public function getWardenRemarks(): ?string
    {
        return $this->wardenRemarks;
    }

    public function setWardenRemarks(?string $wardenRemarks): self
    {
        $this->wardenRemarks = $wardenRemarks;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getOutpass()
    {
        return $this->entityManager
            ->getRepository(Outpass::class)
            ->findAll();
    }
}
