<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\Gender;

class OutpassSettings
{
    public function __construct(
        private readonly Gender $type,
        private readonly array $data
    )
    {
    }

    public function getType(): Gender
    {
        return $this->type;
    }

    public function getParentApproval(): bool
    {
        return (bool) ($this->data['parentApproval'] ?? false);
    }

    public function getLateArrivalGraceMinutes(): int
    {
        return (int) ($this->data['lateArrivalGraceMinutes'] ?? 30);
    }
}
