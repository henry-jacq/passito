<?php

namespace App\Interfaces;

interface AssignmentTargetInterface
{
    public function getId(): int|string;
    public function getName(): string; // or label/title
}
