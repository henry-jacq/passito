<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'parent_verifications')]
class ParentVerification
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: OutpassRequest::class)]
    #[ORM\JoinColumn(name: 'outpass_id', nullable: false)]
    private OutpassRequest $outpassRequest;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $verificationToken;

    #[ORM\Column(type: 'boolean')]
    private bool $isUsed = false;

    #[ORM\Column(type: 'string', enumType: OutpassStatus::class, nullable: true)]
    private ?OutpassStatus $decision = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $verifiedAt = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
}
