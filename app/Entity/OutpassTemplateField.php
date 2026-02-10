<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Validator\Constraints\Choice;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\EntityGetSetTrait;

#[ORM\Entity]
#[ORM\Table(name: 'outpass_template_fields')]
class OutpassTemplateField
{
    use EntityGetSetTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: OutpassTemplate::class, inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private OutpassTemplate $template;

    #[ORM\Column(type: 'string', length: 100)]
    private string $fieldName;

    #[Choice(choices: ['text', 'number', 'date', 'time', 'textarea', 'select', 'boolean', 'datetime', 'email', 'phone'])]
    #[ORM\Column(type: 'string', length: 20)]
    private string $fieldType; // text, number, date, time, textarea, select

    #[ORM\Column(type: 'boolean')]
    private bool $isSystemField = false;
    
    #[ORM\Column(type: 'boolean')]
    private bool $isRequired = false;

    public function __toString(): string
    {
        return sprintf('[%s] %s', $this->template->getName(), $this->fieldName);
    }

    public function getFieldTypeLabel(): string
    {
        return match ($this->fieldType) {
            'text' => 'Text',
            'number' => 'Number',
            'date' => 'Date',
            'time' => 'Time',
            'textarea' => 'Textarea',
            'select' => 'Select',
            default => 'Unknown'
        };
    }

    public function getFieldTypeOptions(): array
    {
        return match ($this->fieldType) {
            'text' => ['placeholder' => 'Enter text'],
            'number' => ['placeholder' => 'Enter number'],
            'date' => ['placeholder' => 'Select date'],
            'time' => ['placeholder' => 'Select time'],
            'textarea' => ['placeholder' => 'Enter text'],
            'select' => $this->options ?? [],
            default => []
        };
    }

    public function isSelectField(): bool
    {
        return $this->fieldType === 'select';
    }

    public function isTextField(): bool
    {
        return $this->fieldType === 'text';
    }

    public function isNumberField(): bool
    {
        return $this->fieldType === 'number';
    }

    public function isDateField(): bool
    {
        return $this->fieldType === 'date';
    }

    public function isTimeField(): bool
    {
        return $this->fieldType === 'time';
    }

    public function isTextareaField(): bool
    {
        return $this->fieldType === 'textarea';
    }

    public function isBooleanField(): bool
    {
        return $this->fieldType === 'boolean';
    }

    public function isDateTimeField(): bool
    {
        return $this->fieldType === 'datetime';
    }

    public function isEmailField(): bool
    {
        return $this->fieldType === 'email';
    }

    public function isPhoneField(): bool
    {
        return $this->fieldType === 'phone';
    }
}
