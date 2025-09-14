<?php

namespace App\Core;

class JobPayloadBuilder
{
    private array $payload = [];
    private array $dependencies = [];

    public static function create(): self
    {
        return new self();
    }

    public function set(string $key, mixed $value): self
    {
        $this->payload[$key] = $value;
        return $this;
    }

    public function addDependency(int|string|array $jobId): self
    {
        // Always flatten: if array passed, unpack it
        $jobIds = is_array($jobId) ? $jobId : [$jobId];
        foreach ($jobIds as $id) {
            if (is_int($id) || ctype_digit((string)$id)) {
                $this->dependencies[] = (int) $id;
            }
        }
        $this->dependencies = array_values(array_unique($this->dependencies));
        return $this;
    }

    public function addDependencies(array $jobIds): self
    {
        foreach ($jobIds as $id) {
            $this->addDependency($id);
        }
        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
