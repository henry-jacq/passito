<?php

namespace App\Interfaces;

interface JobInterface
{
    public function handle(array $payload);
}
