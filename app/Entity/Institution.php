<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\InstitutionType;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'institutions', indexes: [
    new ORM\Index(name: "name_idx", columns: ["name"])
])]
class Institution
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', enumType: InstitutionType::class)]
    private InstitutionType $type;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'address' => $this->getAddress(),
            'type' => $this->getType()->value,
            'created_at' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $this->getUpdatedAt() ? $this->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ];
    }
}
