<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Gender;
use DateTimeInterface;
use App\Enum\ReportKey;
use App\Enum\CronFrequency;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(
    name: 'report_configs',
    uniqueConstraints: [
        new ORM\UniqueConstraint(columns: ['report_key', 'gender'])
    ]
)]
class ReportConfig
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: "string", enumType: ReportKey::class)]
    private ReportKey $reportKey;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => 0])]
    private bool $isEnabled = false;

    #[ORM\Column(type: "string", enumType: Gender::class)]
    private Gender $gender;

    #[ORM\Column(type: "string", enumType: CronFrequency::class)]
    private CronFrequency $frequency;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dayOfWeek = null; // 1=Mon … 7=Sun

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $dayOfMonth = null; // 1–31

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $month = null; // 1–12

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private DateTimeInterface $time;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(
        name: "report_config_recipients",
        joinColumns: [new ORM\JoinColumn(name: "report_config_id", referencedColumnName: "id", onDelete: "CASCADE")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    )]
    private Collection $recipients;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $lastSent = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $nextSend = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ["default" => "CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;

    #[ORM\Column(
        type: Types::DATETIME_MUTABLE,
        options: ["default" => "CURRENT_TIMESTAMP", "on update" => "CURRENT_TIMESTAMP"]
    )]
    private DateTimeInterface $updatedAt;

    
    public function __construct()
    {
        $this->recipients = new ArrayCollection();
    }

    public function addRecipient(User $user): self
    {
        if (!$this->recipients->contains($user)) {
            $this->recipients->add($user);
        }
        return $this;
    }

    public function removeRecipient(User $user): self
    {
        $this->recipients->removeElement($user);
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'reportKey' => $this->getReportKey()->value,
            'isEnabled' => $this->getIsEnabled(),
            'gender' => $this->getGender()->value,
            'frequency' => $this->getFrequency()->value,
            'dayOfWeek' => $this->getDayOfWeek(),
            'dayOfMonth' => $this->getDayOfMonth(),
            'month' => $this->getMonth(),
            'time' => $this->getTime(),
            'recipients' => $this->getRecipients()->map(fn(User $user) => $user->toArray())->toArray(),
            'lastSent' => $this->getLastSent(),
            'nextSend' => $this->getNextSend(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
        ];
    }
}
