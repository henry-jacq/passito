<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'settings')]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $keyName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $value;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function setKeyName(string $keyName): self
    {
        $this->keyName = $keyName;
        return $this;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
