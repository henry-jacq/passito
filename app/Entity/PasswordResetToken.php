<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'password_reset_tokens', indexes: [
    new ORM\Index(name: 'prt_user_idx', columns: ['user_id']),
    new ORM\Index(name: 'prt_expires_idx', columns: ['expiresAt']),
    new ORM\Index(name: 'prt_consumed_idx', columns: ['consumedAt']),
])]
class PasswordResetToken
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    // Store only a hash of the token (never the raw token).
    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $tokenHash;

    #[ORM\Column(type: 'datetime')]
    private DateTime $expiresAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $consumedAt = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'string', length: 45, nullable: true)]
    private ?string $requestIp = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $userAgent = null;
}

