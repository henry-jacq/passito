<?php

namespace App\Core;

class JobPayloadBuilder
{
    private array $payload = [];

    public static function create(): self
    {
        return new self();
    }

    public function set(string $key, mixed $value): self
    {
        $this->payload[$key] = $value;
        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
