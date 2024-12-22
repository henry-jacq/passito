<?php

namespace App\Enum;

enum UserRole: string {
    case ADMIN = 'admin';
    case USER = 'user';

    public static function isAdmin(string $role): bool {
        return self::tryFrom($role) === self::ADMIN;
    }

    public static function isUser(string $role): bool {
        return self::tryFrom($role) === self::USER;
    }
}
