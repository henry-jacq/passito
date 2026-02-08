<?php

declare(strict_types=1);

namespace App\Dto;

use DateTime;
use App\Entity\Student;
use App\Entity\OutpassTemplate;

class CreateOutpassDto
{
    private function __construct(
        private readonly Student $student,
        private readonly OutpassTemplate $template,
        private readonly DateTime $fromDate,
        private readonly DateTime $toDate,
        private readonly DateTime $fromTime,
        private readonly DateTime $toTime,
        private readonly string $destination,
        private readonly string $reason,
        private readonly array $attachments,
        private readonly ?array $customValues
    ) {}

    public static function create(
        Student $student,
        OutpassTemplate $template,
        DateTime $fromDate,
        DateTime $toDate,
        DateTime $fromTime,
        DateTime $toTime,
        string $destination,
        string $reason = 'N/A',
        array $attachments = [],
        ?array $customValues = null
    ): self {
        // Validate date range
        if ($fromDate > $toDate) {
            throw new \InvalidArgumentException('From date cannot be after to date');
        }

        // Validate time range (only if same day)
        if ($fromDate->format('Y-m-d') === $toDate->format('Y-m-d') && $fromTime > $toTime) {
            throw new \InvalidArgumentException('From time cannot be after to time on the same day');
        }

        // Validate destination
        $destination = trim($destination);
        if (empty($destination)) {
            throw new \InvalidArgumentException('Destination cannot be empty');
        }

        // Validate reason
        $reason = trim($reason);
        if (empty($reason)) {
            $reason = 'N/A';
        }

        return new self(
            $student,
            $template,
            $fromDate,
            $toDate,
            $fromTime,
            $toTime,
            $destination,
            $reason,
            $attachments,
            $customValues
        );
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getTemplate(): OutpassTemplate
    {
        return $this->template;
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

    public function getDestination(): string
    {
        return $this->destination;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getCustomValues(): ?array
    {
        return $this->customValues;
    }

    public function toArray(): array
    {
        return [
            'student' => $this->student,
            'template' => $this->template,
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
            'from_time' => $this->fromTime,
            'to_time' => $this->toTime,
            'destination' => $this->destination,
            'reason' => $this->reason,
            'attachments' => $this->attachments,
            'custom_values' => $this->customValues,
        ];
    }
}
