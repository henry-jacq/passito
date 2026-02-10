<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'academic_years')]
class AcademicYear
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', unique: true)]
    protected string $label; // e.g. "UG2022-2026"

    #[ORM\Column(type: 'integer', name: 'start_year', nullable: true)]
    protected ?int $startYear = null;

    #[ORM\Column(type: 'integer', name: 'end_year', nullable: true)]
    protected ?int $endYear = null;

    #[ORM\Column(type: 'boolean')]
    protected bool $status = true;

    #[ORM\Column(type: 'datetime')]
    protected DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'start_year' => $this->getStartYear(),
            'end_year' => $this->getEndYear(),
            'status' => $this->getStatus(),
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
