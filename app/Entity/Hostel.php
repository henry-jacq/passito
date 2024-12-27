<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'hostels')]
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

    #[ORM\Column(type: 'string', length: 255)]
    private string $hostelType;
}
