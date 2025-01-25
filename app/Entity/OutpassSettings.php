<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Gender;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_settings')]
class OutpassSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $dailyLimit = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $weeklyLimit = null;

    #[ORM\Column(type: 'string', enumType: Gender::class)]
    private Gender $type;

    #[ORM\Column(type: 'boolean')]
    private bool $parentApproval;

    #[ORM\Column(type: 'boolean')]
    private bool $companionVerification;

    #[ORM\Column(type: 'boolean')]
    private bool $emergencyContactNotification;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekdayCollegeHoursStart = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekdayCollegeHoursEnd = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekdayOvernightStart = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekdayOvernightEnd = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekendStartTime = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTime $weekendEndTime = null;

    #[ORM\Column(type: 'boolean')]
    private bool $emailNotification;

    #[ORM\Column(type: 'boolean')]
    private bool $smsNotification;

    #[ORM\Column(type: 'boolean')]
    private bool $appNotification;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDailyLimit(): ?int
    {
        return $this->dailyLimit;
    }

    public function setDailyLimit(?int $dailyLimit): self
    {
        $this->dailyLimit = $dailyLimit;
        return $this;
    }

    public function getType(): Gender
    {
        return $this->type;
    }

    public function setType(Gender $gender): self
    {
        $this->type = $gender;
        return $this;
    }

    public function getWeeklyLimit(): ?int
    {
        return $this->weeklyLimit;
    }

    public function setWeeklyLimit(?int $weeklyLimit): self
    {
        $this->weeklyLimit = $weeklyLimit;
        return $this;
    }

    public function isParentApproval(): bool
    {
        return $this->parentApproval;
    }

    public function setParentApproval(bool $parentApproval): self
    {
        $this->parentApproval = $parentApproval;
        return $this;
    }

    public function isCompanionVerification(): bool
    {
        return $this->companionVerification;
    }

    public function setCompanionVerification(bool $companionVerification): self
    {
        $this->companionVerification = $companionVerification;
        return $this;
    }

    public function isEmergencyContactNotification(): bool
    {
        return $this->emergencyContactNotification;
    }

    public function setEmergencyContactNotification(bool $emergencyContactNotification): self
    {
        $this->emergencyContactNotification = $emergencyContactNotification;
        return $this;
    }

    public function getWeekdayCollegeHoursStart(): ?\DateTime
    {
        return $this->weekdayCollegeHoursStart;
    }

    public function setWeekdayCollegeHoursStart(?\DateTime $weekdayCollegeHoursStart): self
    {
        $this->weekdayCollegeHoursStart = $weekdayCollegeHoursStart;
        return $this;
    }

    public function getWeekdayCollegeHoursEnd(): ?\DateTime
    {
        return $this->weekdayCollegeHoursEnd;
    }

    public function setWeekdayCollegeHoursEnd(?\DateTime $weekdayCollegeHoursEnd): self
    {
        $this->weekdayCollegeHoursEnd = $weekdayCollegeHoursEnd;
        return $this;
    }

    public function getWeekdayOvernightStart(): ?\DateTime
    {
        return $this->weekdayOvernightStart;
    }

    public function setWeekdayOvernightStart(?\DateTime $weekdayOvernightStart): self
    {
        $this->weekdayOvernightStart = $weekdayOvernightStart;
        return $this;
    }

    public function getWeekdayOvernightEnd(): ?\DateTime
    {
        return $this->weekdayOvernightEnd;
    }

    public function setWeekdayOvernightEnd(?\DateTime $weekdayOvernightEnd): self
    {
        $this->weekdayOvernightEnd = $weekdayOvernightEnd;
        return $this;
    }

    public function getWeekendStartTime(): ?\DateTime
    {
        return $this->weekendStartTime;
    }

    public function setWeekendStartTime(?\DateTime $weekendStartTime): self
    {
        $this->weekendStartTime = $weekendStartTime;
        return $this;
    }

    public function getWeekendEndTime(): ?\DateTime
    {
        return $this->weekendEndTime;
    }

    public function setWeekendEndTime(?\DateTime $weekendEndTime): self
    {
        $this->weekendEndTime = $weekendEndTime;
        return $this;
    }

    public function isEmailNotification(): bool
    {
        return $this->emailNotification;
    }

    public function setEmailNotification(bool $emailNotification): self
    {
        $this->emailNotification = $emailNotification;
        return $this;
    }

    public function isSmsNotification(): bool
    {
        return $this->smsNotification;
    }

    public function setSmsNotification(bool $smsNotification): self
    {
        $this->smsNotification = $smsNotification;
        return $this;
    }

    public function isAppNotification(): bool
    {
        return $this->appNotification;
    }

    public function setAppNotification(bool $appNotification): self
    {
        $this->appNotification = $appNotification;
        return $this;
    }
}
