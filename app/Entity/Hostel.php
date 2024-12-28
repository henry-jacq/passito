<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\HostelType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'hostels', indexes: [
    new ORM\Index(name: "hostel_type_idx", columns: ["hostelType"])
])]
class Hostel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Institution::class)]
    #[ORM\JoinColumn(name: 'institution_id', referencedColumnName: 'id', nullable: false)]
    private Institution $institution;

    #[ORM\ManyToOne(targetEntity: Warden::class)]
    #[ORM\JoinColumn(name: 'warden_id', referencedColumnName: 'id', nullable: false)]
    private Warden $warden;

    #[ORM\Column(type: 'string', length: 255)]
    private string $hostelName;

    #[ORM\Column(type: 'string', enumType: HostelType::class)]
    private string $hostelType;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInstitution(): Institution
    {
        return $this->institution;
    }

    public function setInstitution(Institution $institution): void
    {
        $this->institution = $institution;
    }

    public function getWarden(): Warden
    {
        return $this->warden;
    }

    public function setWarden(Warden $warden): void
    {
        $this->warden = $warden;
    }

    public function getHostelName(): string
    {
        return $this->hostelName;
    }

    public function setHostelName(string $hostelName): void
    {
        $this->hostelName = $hostelName;
    }

    public function getHostelType(): string
    {
        return $this->hostelType;
    }

    public function setHostelType(string $hostelType): void
    {
        $this->hostelType = $hostelType;
    }
}
