<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\Gender;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_templates')]
class OutpassTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'boolean')]
    private bool $isSystemTemplate = false;

    #[ORM\Column(type: 'string', enumType: Gender::class)]
    private Gender $gender;

    #[ORM\Column(type: 'boolean')]
    private bool $allowAttachments = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\OneToMany(mappedBy: 'template', targetEntity: OutpassTemplateField::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $fields;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->fields = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function isSystemTemplate(): bool
    {
        return $this->isSystemTemplate;
    }

    public function setSystemTemplate(bool $isSystemTemplate): self
    {
        $this->isSystemTemplate = $isSystemTemplate;
        return $this;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }
    
    public function isAllowAttachments(): bool
    {
        return $this->allowAttachments;
    }

    public function setAllowAttachments(bool $allowAttachments): self
    {
        $this->allowAttachments = $allowAttachments;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(OutpassTemplateField $field): void
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
            $field->setTemplate($this);
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
