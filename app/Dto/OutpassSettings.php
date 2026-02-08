<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\Gender;

class OutpassSettings
{
    public function __construct(
        private readonly Gender $type,
        private readonly array $data
    )
    {
    }

    public function getType(): Gender
    {
        return $this->type;
    }

    public function getWeekdayCollegeHoursStart(): ?\DateTime
    {
        return $this->toTime($this->data['weekdayCollegeHoursStart'] ?? null);
    }

    public function getWeekdayCollegeHoursEnd(): ?\DateTime
    {
        return $this->toTime($this->data['weekdayCollegeHoursEnd'] ?? null);
    }

    public function getWeekdayOvernightStart(): ?\DateTime
    {
        return $this->toTime($this->data['weekdayOvernightStart'] ?? null);
    }

    public function getWeekdayOvernightEnd(): ?\DateTime
    {
        return $this->toTime($this->data['weekdayOvernightEnd'] ?? null);
    }

    public function getWeekendStartTime(): ?\DateTime
    {
        return $this->toTime($this->data['weekendStartTime'] ?? null);
    }

    public function getWeekendEndTime(): ?\DateTime
    {
        return $this->toTime($this->data['weekendEndTime'] ?? null);
    }

    public function getParentApproval(): bool
    {
        return (bool) ($this->data['parentApproval'] ?? false);
    }

    public function getLateArrivalGraceMinutes(): int
    {
        return (int) ($this->data['lateArrivalGraceMinutes'] ?? 30);
    }

    private function toTime(?string $value): ?\DateTime
    {
        if (!$value) {
            return null;
        }

        $parsed = \DateTime::createFromFormat('H:i', $value);
        if ($parsed) {
            return $parsed;
        }

        $parsedWithSeconds = \DateTime::createFromFormat('H:i:s', $value);
        return $parsedWithSeconds ?: null;
    }
}
