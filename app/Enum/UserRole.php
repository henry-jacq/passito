<?php

namespace App\Enum;

enum UserRole: string {
    case USER = 'student';
    case ADMIN = 'warden';
    case SUPER_ADMIN = 'chief_warden';

    public static function isStudent(string $role): bool {
        return self::tryFrom($role) === self::USER;
    }

    public static function isAdmin(string $role): bool {
        return self::tryFrom($role) === self::ADMIN;
    }

    public static function isSuperAdmin(string $role): bool {
        return self::tryFrom($role) === self::SUPER_ADMIN;
    }

    public static function isAdministrator(string $role): bool {
        return in_array($role, [self::ADMIN->value, self::SUPER_ADMIN->value]);
    }

    public static function isValidRole(string $role): bool {
        return in_array($role, [self::USER->value, self::ADMIN->value, self::SUPER_ADMIN->value]);
    }
}
