<?php

namespace App\Enum;

enum InstitutionType: string {
    case COLLEGE = 'college';
    case UNIVERSITY = 'university';

    public static function isCollege(string $type): bool {
        return self::tryFrom($type) === self::COLLEGE;
    }

    public static function isUniversity(string $type): bool {
        return self::tryFrom($type) === self::UNIVERSITY;
    }

    // Validate if the type is valid
    public static function isValidInstitution(string $type): bool {
        return in_array($type, [self::COLLEGE->value, self::UNIVERSITY->value]);
    }

    public static function values(): array {
        return [self::COLLEGE->value, self::UNIVERSITY->value];
    }
}
