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
}
