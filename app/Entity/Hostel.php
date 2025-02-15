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

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'warden_id', referencedColumnName: 'id', nullable: false)]
    private User $warden;

    #[ORM\Column(type: 'string', length: 255)]
    private string $hostelName;

    #[ORM\Column(type: 'string', enumType: HostelType::class)]
    private HostelType $hostelType;

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

    public function getWarden(): User
    {
        return $this->warden;
    }

    public function setWarden(User $warden): void
    {
        $this->warden = $warden;
    }

    public function getName(): string
    {
        return $this->hostelName;
    }

    public function setName(string $hostelName): void
    {
        $this->hostelName = $hostelName;
    }

    public function getHostelType(): HostelType
    {
        return $this->hostelType;
    }

    public function setHostelType(HostelType $hostelType): void
    {
        $this->hostelType = $hostelType;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'institution' => $this->getInstitution()->toArray(),
            'warden' => $this->getWarden()->toArray(),
            'hostelName' => $this->getName(),
            'hostelType' => $this->getHostelType()->value
        ];
    }
}
