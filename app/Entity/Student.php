<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'students', indexes: [
    new ORM\Index(name: "roll_no_idx", columns: ["rollNo"]),
    new ORM\Index(name: "year_idx", columns: ["year"]),
    new ORM\Index(name: "academic_year_idx", columns: ["academic_year_id"])
])]
class Student
{
    use EntityGetSetTrait;

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
    private int $rollNo;

    #[ORM\Column(type: 'integer')]
    private int $year;

    #[ORM\Column(type: 'string', length: 255)]
    private string $roomNo;

    #[ORM\Column(type: 'string', length: 15)]
    private string $parentNo;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser()->toArray(),
            'hostel' => $this->getHostel()->toArray(),
            'roll_no' => $this->getRollNo(),
            'year' => $this->getYear(),
            'academic_year' => $this->getAcademicYear()?->toArray(),
            'program' => $this->getProgram()->toArray(),
            'room_no' => $this->getRoomNo(),
            'parent_no' => $this->getParentNo(),
            'updated_at' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}
