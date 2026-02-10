<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'system_settings')]
class SystemSettings
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $keyName;

    #[ORM\Column(type: 'text')]
    private string $value;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;
}
