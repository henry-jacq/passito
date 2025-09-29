<?php

declare(strict_types=1);

namespace App\Enum;

use App\Entity\Hostel;
use App\Entity\AcademicYear;

enum AssignmentTarget: string
{
    case HOSTEL = 'hostel';
    case ACADEMIC_YEAR = 'academic_year';

    /**
     * Get a human-readable label for the enum.
     */
    public function label(): string
    {
        return match ($this) {
            self::HOSTEL => 'Hostel Wise',
            self::ACADEMIC_YEAR => 'Academic Year',
        };
    }

    /**
     * Map enum case to the entity class it represents.
     */
    public function entityClass(): string
    {
        return match ($this) {
            self::HOSTEL => Hostel::class,
            self::ACADEMIC_YEAR => AcademicYear::class,
        };
    }

    /**
     * Check if given entity class matches the enum case.
     */
    public function matchesEntity(object $entity): bool
    {
        return $entity instanceof ($this->entityClass());
    }

    public static function getLabels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->name] = $case->label();
        }
        return $labels;
    }
}
