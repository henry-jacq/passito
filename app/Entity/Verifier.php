<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\VerifierMode;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'verifiers')]
class Verifier
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $verifierName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $location;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ipAddress;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $machineId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $authToken;

    #[ORM\Column(type: 'string', enumType: VerifierMode::class)]
    private VerifierMode $type = VerifierMode::AUTOMATED;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $lastSync;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;
}
