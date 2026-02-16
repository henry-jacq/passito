<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'login_sessions', indexes: [
    new ORM\Index(name: 'login_session_user_idx', columns: ['user_id']),
    new ORM\Index(name: 'login_session_token_idx', columns: ['token_id']),
    new ORM\Index(name: 'login_session_active_idx', columns: ['is_active']),
])]
class LoginSession
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(name: 'token_id', type: Types::STRING, length: 64, unique: true)]
    private string $tokenId;

    #[ORM\Column(name: 'ip_address', type: Types::STRING, length: 45, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(name: 'user_agent', type: Types::STRING, length: 255, nullable: true)]
    private ?string $userAgent = null;

    #[ORM\Column(name: 'is_active', type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'expires_at', type: Types::DATETIME_MUTABLE)]
    private DateTime $expiresAt;

    #[ORM\Column(name: 'revoked_at', type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $revokedAt = null;
}
