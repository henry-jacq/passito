<?php

namespace App\Enum;

enum OutpassType: string {
    case OUTING = 'outing';
    case HOME = 'home';
    case OTHER = 'other';

    public static function isOuting(string $type): bool {
        return self::tryFrom($type) === self::OUTING;
    }

    public static function isHome(string $type): bool {
        return self::tryFrom($type) === self::HOME;
    }

    public static function isOther(string $type): bool {
        return self::tryFrom($type) === self::OTHER;
    }

    public static function isValidType(string $type): bool {
        return in_array($type, [self::OUTING->value, self::HOME->value, self::OTHER->value]);
    }
}
