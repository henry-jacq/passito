<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;
use App\Interfaces\SessionInterface;

class Session implements SessionInterface
{
    public array $options;
    
    public function __construct(private readonly Config $config)
    {
        $this->options = $this->config->get('session');
    }

    public function start(): void
    {
        if ($this->isActive()) {
            throw new RuntimeException('Session has already been started');
        }

        if (headers_sent($fileName, $line)) {
            throw new RuntimeException('Headers have already sent by ' . $fileName . ':' . $line);
        }

        // session_set_cookie_params(
        //     [
        //         'secure'   => $this->options['secure'],
        //         'httponly' => $this->options['httponly'],
        //         'samesite' => $this->options['samesite'],
        //     ]
        // );

        if (!empty($this->options['name'])) {
            session_name($this->options['name']);
        }

        if (!session_start()) {
            throw new RuntimeException('Unable to start the session');
        }
    }

    public function save(): void
    {
        session_write_close();
    }

    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $_SESSION[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, array $messages): void
    {
        $_SESSION[$this->options['flash_name']][$key] = $messages;
    }

    public function getFlash(string $key): array
    {
        $messages = $_SESSION[$this->options['flash_name']][$key] ?? [];

        unset($_SESSION[$this->options['flash_name']][$key]);

        return $messages;
    }
}