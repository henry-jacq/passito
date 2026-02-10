<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: "jobs")]
class Job
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private string $type;

    #[ORM\Column(type: "json")]
    private array $payload;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $availableAt;

    #[ORM\Column(type: "integer")]
    private int $attempts = 0;

    #[ORM\Column(type: "integer")]
    private int $maxAttempts = 3;

    #[ORM\Column(type: "string", length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $createdAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $lastError = null;


    public function __construct(string $type, array $payload, int $delay = 0, int $maxAttempts = 3)
    {
        $this->type = $type;
        $this->payload = $payload;
        $this->availableAt = new \DateTime("+{$delay} seconds");
        $this->maxAttempts = $maxAttempts;
        $this->status = 'pending';
        $this->createdAt = new \DateTime();
    }

    public function incrementAttempts(): void
    {
        $this->attempts++;
    }

}
