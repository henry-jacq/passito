<?php

declare(strict_types=1);

namespace App\Enum;

enum VerifierMode: string
{
    case AUTOMATED = 'automated';
    case MANUAL = 'manual';
    case BOTH = 'both';
}
