<?php

namespace App\Enum;

enum OutpassStatus: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function isPending(string $status): bool {
        return self::tryFrom($status) === self::PENDING;
    }

    public static function isApproved(string $status): bool {
        return self::tryFrom($status) === self::APPROVED;
    }

    public static function isRejected(string $status): bool {
        return self::tryFrom($status) === self::REJECTED;
    }

    public static function isValidStatus(string $role): bool {
        return in_array($role, [self::PENDING->value, self::APPROVED->value, self::REJECTED->value]);
    }
}
