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
    private int $id;

    #[ORM\Column(type: 'string', length: 9, unique: true)]
    private string $label; // e.g. "UG2022-2026"

    #[ORM\Column(type: 'integer', name: 'start_year')]
    private int $startYear;

    #[ORM\Column(type: 'integer', name: 'end_year')]
    private int $endYear;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
