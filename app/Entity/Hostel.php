<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\HostelType;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'hostels', indexes: [
    new ORM\Index(name: "hostel_type_idx", columns: ["hostelType"])
])]
class Hostel
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $hostelName;

    #[ORM\Column(type: 'string', enumType: HostelType::class)]
    private HostelType $hostelType;

    #[ORM\Column(type: 'string', length: 255)]
    private string $category;

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'category' => $this->getCategory(),
            'hostelName' => $this->getHostelName(),
            'hostelType' => $this->getHostelType()->value
        ];
    }
}
