<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'students', indexes: [
    new ORM\Index(name: "digital_id_idx", columns: ["digitalId"]),
    new ORM\Index(name: "year_idx", columns: ["year"]),
    new ORM\Index(name: "academic_year_idx", columns: ["academic_year_id"])
])]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Hostel::class)]
    #[ORM\JoinColumn(name: 'hostel_id', referencedColumnName: 'id', nullable: false)]
    private Hostel $hostel;

    #[ORM\ManyToOne(targetEntity: InstitutionProgram::class)]
    #[ORM\JoinColumn(name: 'program_id', referencedColumnName: 'id', nullable: false)]
    private InstitutionProgram $program;

    #[ORM\ManyToOne(targetEntity: AcademicYear::class)]
    #[ORM\JoinColumn(name: 'academic_year_id', referencedColumnName: 'id', nullable: true)]
    private ?AcademicYear $academicYear = null;

    #[ORM\Column(type: 'integer', unique: true)]
    private int $digitalId;

    #[ORM\Column(type: 'integer')]
    private int $year;

    #[ORM\Column(type: 'string', length: 255)]
    private string $roomNo;

    #[ORM\Column(type: 'string', length: 15)]
    private string $parentNo;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;


    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getHostel(): Hostel
    {
        return $this->hostel;
    }

    public function setHostel(Hostel $hostel): void
    {
        $this->hostel = $hostel;
    }

    public function getProgram(): InstitutionProgram
    {
        return $this->program;
    }

    public function setProgram(InstitutionProgram $program): void
    {
        $this->program = $program;
    }

    public function getDigitalId(): int
    {
        return $this->digitalId;
    }

    public function setDigitalId(int $digitalId): void
    {
        $this->digitalId = $digitalId;
    }

    public function getAcademicYear(): ?AcademicYear
    {
        return $this->academicYear;
    }

    public function setAcademicYear(?AcademicYear $academicYear): void
    {
        $this->academicYear = $academicYear;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getRoomNo(): string
    {
        return $this->roomNo;
    }

    public function setRoomNo(string $roomNo): void
    {
        $this->roomNo = $roomNo;
    }

    public function getParentNo(): string
    {
        return $this->parentNo;
    }

    public function setParentNo(string $parentNo): void
    {
        $this->parentNo = $parentNo;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser()->toArray(),
            'hostel' => $this->getHostel()->toArray(),
            'digital_id' => $this->getDigitalId(),
            'year' => $this->getYear(),
            'academic_year' => $this->getAcademicYear()?->toArray(),
            'program' => $this->getProgram()->toArray(),
            'room_no' => $this->getRoomNo(),
            'parent_no' => $this->getParentNo(),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
