<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'verifiers')]
class Verifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $verifierName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $location;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255)]
    private string $ipAddress;

    #[ORM\Column(type: 'string', length: 255)]
    private string $machineId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $oneTimeToken;

    #[ORM\Column(type: 'datetime')]
    private DateTime $lastSync;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;
}
