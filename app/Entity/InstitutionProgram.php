<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'institution_programs', indexes: [
    new ORM\Index(name: "program_name_idx", columns: ["programName"]),
    new ORM\Index(name: "short_code_idx", columns: ["shortCode"])
], uniqueConstraints: [
    new ORM\UniqueConstraint(name: "uniq_institution_short_code", columns: ["provided_by", "shortCode"])
])]
class InstitutionProgram
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'provided_by', referencedColumnName: 'id', nullable: false)]
    private Institution $providedBy;

    #[ORM\Column(type: 'string', length: 255)]
    private string $programName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $courseName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $shortCode;

    #[ORM\Column(type: 'integer')]
    private int $duration;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;


    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'providedBy' => $this->getProvidedBy()->getId(),
            'programName' => $this->getProgramName(),
            'courseName' => $this->getCourseName(),
            'shortCode' => $this->getShortCode(),
            'duration' => $this->getDuration(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
