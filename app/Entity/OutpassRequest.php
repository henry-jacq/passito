<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\OutpassStatus;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_requests', indexes: [
    new ORM\Index(name: "pass_type_idx", columns: ["passType"]),
    new ORM\Index(name: "status_idx", columns: ["status"]),
    new ORM\Index(name: "created_at_idx", columns: ["created_at"])
])]
class OutpassRequest
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: OutpassTemplate::class)]
    #[ORM\JoinColumn(name: 'template_id', referencedColumnName: 'id', nullable: false)]
    private OutpassTemplate $template;
    
    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
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
    private string $destination;

    #[ORM\Column(type: 'string', length: 255)]
    private string $reason;

    #[ORM\Column(type: 'string', enumType: OutpassStatus::class)]
    private OutpassStatus $status;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $customValues = null;

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
