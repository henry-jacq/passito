<?php

declare(strict_types=1);

namespace App\Enum;

enum CronFrequency: string
{
    case DAILY   = 'daily';
    case WEEKLY  = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY  = 'yearly';


    public static function isValid(string $frequency): bool
    {
        return self::tryFrom($frequency) !== null;
    }

    public static function getAllFrequencies(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::DAILY   => 'Once every day',
            self::WEEKLY  => 'Once every week',
            self::MONTHLY => 'Once every month',
            self::YEARLY  => 'Once every year',
        };
    }    
}
