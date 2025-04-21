<?php

namespace App\Enum;

enum OutpassStatus: string
{
    case PENDING = 'pending';
    case PARENT_PENDING = 'parent_pending';
    case PARENT_APPROVED = 'parent_approved';
    case PARENT_DENIED = 'parent_denied';
    case APPROVED = 'approved'; // Final approval by admin after parent
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';

    public static function isPending(string $status): bool
    {
        return self::tryFrom($status) === self::PENDING;
    }

    public static function isApproved(string $status): bool
    {
        return self::tryFrom($status) === self::APPROVED;
    }

    public static function isRejected(string $status): bool
    {
        return self::tryFrom($status) === self::REJECTED;
    }

    public static function isParentPending(string $status): bool
    {
        return self::tryFrom($status) === self::PARENT_PENDING;
    }

    public static function isParentApproved(string $status): bool
    {
        return self::tryFrom($status) === self::PARENT_APPROVED;
    }

    public static function isDeniedByParent(string $status): bool
    {
        return self::tryFrom($status) === self::PARENT_DENIED;
    }

    public static function isExpired(string $status): bool
    {
        return self::tryFrom($status) === self::EXPIRED;
    }

    public static function isValidStatus(string $status): bool
    {
        return in_array($status, array_column(self::cases(), 'value'));
    }
}
