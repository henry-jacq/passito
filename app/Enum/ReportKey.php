<?php

namespace App\Enum;

enum ReportKey: string
{
    case DAILY_MOVEMENT = 'daily_movement';
    case LATE_ARRIVALS = 'late_arrivals';

    public static function keys(): array
    {
        return array_map(fn(self $key) => $key->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::DAILY_MOVEMENT => 'Daily Movement Report',
            self::LATE_ARRIVALS => 'Late Arrivals Report',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DAILY_MOVEMENT => 'Summary of daily check-ins and check-outs of students.',
            self::LATE_ARRIVALS => 'List of students who have arrived late beyond the allowed time.',
        };
    }

    public function display(): string
    {
        return match ($this) {
            self::DAILY_MOVEMENT => 'Daily Movement',
            self::LATE_ARRIVALS => 'Late Arrivals',
        };
    }
}
