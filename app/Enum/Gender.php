<?php

namespace App\Enum;

enum Gender: string {
    case MALE = "male";
    case FEMALE = "female";

    public static function isMale(string $gender): bool {
        return self::tryFrom($gender) === self::MALE;
    }

    public static function isFemale(string $gender): bool {
        return self::tryFrom($gender) === self::FEMALE;
    }

    public static function values(): array
    {
        return [self::MALE->value, self::FEMALE->value];
    }

    public static function isValid(string $gender): bool
    {
        return in_array($gender, self::values(), true);
    }

    public static function all(): array 
    {
        return [self::MALE, self::FEMALE];
    }
}
