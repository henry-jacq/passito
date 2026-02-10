<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use App\Enum\Gender;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_templates')]
class OutpassTemplate
{
    use EntityGetSetTrait;

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
