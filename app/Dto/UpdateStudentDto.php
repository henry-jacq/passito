<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Hostel;
use App\Entity\InstitutionProgram;
use App\Entity\AcademicYear;

class UpdateStudentDto
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $contact,
        private readonly int $digitalId,
        private readonly int $year,
        private readonly string $roomNo,
        private readonly string $parentNo,
        private readonly Hostel $hostel,
        private readonly InstitutionProgram $program,
        private readonly ?AcademicYear $academicYear = null,
        private readonly ?bool $status = null
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        // Validate name
        if (empty(trim($this->name))) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        // Validate email
        $email = $this->getEmail();
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        // Validate contact
        if (empty(trim($this->contact))) {
            throw new \InvalidArgumentException('Contact number cannot be empty');
        }

        // Validate digital ID
        if ($this->digitalId <= 0) {
            throw new \InvalidArgumentException('Digital ID must be a positive integer');
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return strtolower(trim($this->email));
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getDigitalId(): int
    {
        return $this->digitalId;
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->getEmail(),
            'contact' => $this->contact,
            'digital_id' => $this->digitalId,
            'year' => $this->year,
            'room_no' => $this->roomNo,
            'parent_no' => $this->parentNo,
            'hostel' => $this->hostel,
            'program' => $this->program,
            'academic_year' => $this->academicYear,
            'status' => $this->status,
        ];
    }

    /**
     * Create DTO from API request data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            contact: $data['contact'],
            digitalId: (int) $data['digital_id'],
            year: (int) $data['year'],
            roomNo: $data['room_no'],
            parentNo: $data['parent_no'],
            hostel: $data['hostel'],
            program: $data['program'],
            academicYear: $data['academic_year'] ?? null,
            status: $data['status'] ?? null
        );
    }
}
