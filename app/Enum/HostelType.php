<?php

namespace App\Enum;

enum HostelType: string {
    case GENTS = 'gents';
    case LADIES = 'ladies';

    public static function isGents(string $type): bool {
        return self::tryFrom($type) === self::GENTS;
    }

    public static function isLadies(string $type): bool {
        return self::tryFrom($type) === self::LADIES;
    }

    // Validate if the type is valid
    public static function isValidHostelType(string $type): bool {
        return in_array($type, [self::GENTS->value, self::LADIES->value]);
    }
}
