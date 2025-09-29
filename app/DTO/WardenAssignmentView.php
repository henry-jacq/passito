<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\WardenAssignment;

class WardenAssignmentView
{
    public function __construct(
        public WardenAssignment $assignment,
        public ?object $resolvedTarget
    ) {}
}
