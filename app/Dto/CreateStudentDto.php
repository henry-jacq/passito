<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Hostel;
use App\Entity\InstitutionProgram;
use App\Entity\AcademicYear;

class CreateStudentDto
{
    public function __construct(
        private readonly int $rollNo,
        private readonly int $year,
        private readonly string $roomNo,
        private readonly string $parentNo,
        private readonly Hostel $hostel,
        private readonly InstitutionProgram $program,
        private readonly ?AcademicYear $academicYear = null
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        // Validate roll no
        if ($this->rollNo <= 0) {
            throw new \InvalidArgumentException('Roll No must be a positive integer');
        }

        // Validate year against program duration
        if ($this->year < 1 || $this->year > $this->program->getDuration()) {
            throw new \InvalidArgumentException(
                sprintf('Year must be between 1 and %d for this program', $this->program->getDuration())
            );
        }

        // Validate room number
        if (empty(trim($this->roomNo))) {
            throw new \InvalidArgumentException('Room number cannot be empty');
        }

        // Validate parent number
        if (empty(trim($this->parentNo))) {
            throw new \InvalidArgumentException('Parent contact number cannot be empty');
        }
    }

    public function getRollNo(): int
    {
        return $this->rollNo;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getRoomNo(): string
    {
        return $this->roomNo;
    }

    public function getParentNo(): string
    {
        return $this->parentNo;
    }

    public function getHostel(): Hostel
    {
        return $this->hostel;
    }

    public function getProgram(): InstitutionProgram
    {
        return $this->program;
    }

    public function getAcademicYear(): ?AcademicYear
    {
        return $this->academicYear;
    }

    public function toArray(): array
    {
        return [
            'roll_no' => $this->rollNo,
            'year' => $this->year,
            'room_no' => $this->roomNo,
            'parent_no' => $this->parentNo,
            'hostel' => $this->hostel,
            'program' => $this->program,
            'academic_year' => $this->academicYear,
        ];
    }

    /**
     * Create DTO from API request data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            rollNo: (int) $data['roll_no'],
            year: (int) $data['year'],
            roomNo: $data['room_no'],
            parentNo: $data['parent_no'],
            hostel: $data['hostel'],
            program: $data['program'],
            academicYear: $data['academic_year'] ?? null
        );
    }
}
