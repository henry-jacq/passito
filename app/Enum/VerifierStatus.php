<?php

namespace App\Enum;

enum VerifierStatus: string {
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function isPending(string $status): bool {
        return self::tryFrom($status) === self::PENDING;
    }

    public static function isActive(string $status): bool {
        return self::tryFrom($status) === self::ACTIVE;
    }

    public static function isInactive(string $status): bool {
        return self::tryFrom($status) === self::INACTIVE;
    }

    public static function isValidStatus(string $role): bool {
        return in_array($role, [self::PENDING->value, self::ACTIVE->value, self::INACTIVE->value]);
    }
}
