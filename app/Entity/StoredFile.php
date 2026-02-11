<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\ResourceType;
use App\Enum\ResourceVisibility;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'files', indexes: [
    new ORM\Index(name: 'file_uuid_idx', columns: ['uuid']),
    new ORM\Index(name: 'file_owner_idx', columns: ['owner_user_id']),
    new ORM\Index(name: 'file_resource_idx', columns: ['resource_type', 'resource_id']),
])]
class StoredFile
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true)]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $storagePath;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $originalName;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $mimeType;

    #[ORM\Column(type: Types::BIGINT)]
    private int $size;

    #[ORM\Column(type: Types::STRING, enumType: ResourceType::class)]
    private ResourceType $resourceType = ResourceType::GENERAL;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $resourceId = null;

    #[ORM\Column(type: Types::STRING, enumType: ResourceVisibility::class)]
    private ResourceVisibility $visibility = ResourceVisibility::OWNER;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'owner_user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $ownerUser = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updatedAt = null;
}
