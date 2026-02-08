<?php

declare(strict_types=1);

namespace App\Dto;

class LoginDto
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        // Validate email
        $email = $this->getEmail();
        if (empty($email)) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        // Validate password
        if (empty($this->password)) {
            throw new \InvalidArgumentException('Password cannot be empty');
        }

        if (strlen($this->password) < 3) {
            throw new \InvalidArgumentException('Password must be at least 3 characters long');
        }
    }

    public function getEmail(): string
    {
        return strtolower(trim($this->email));
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->password,
        ];
    }

    /**
     * Create DTO from API request data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'] ?? '',
            password: $data['password'] ?? ''
        );
    }
}
