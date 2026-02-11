<?php

namespace App\Services;

use App\Core\Config;

class SecureLinkService
{
    private string $secret;

    public function __construct(private readonly Config $config)
    {
        $secret = (string) $this->config->get('app.link_secret', '');
        if ($secret === '') {
            $secret = (string) $this->config->get('jwt.secret', '');
        }
        $this->secret = $secret !== '' ? $secret : 'change-me';
    }

    public function generateToken(string $type, string $id): string
    {
        $payload = $type . ':' . $id;
        $sig = hash_hmac('sha256', $payload, $this->secret);
        $raw = $payload . ':' . $sig;

        return $this->base64UrlEncode($raw);
    }

    public function parseToken(string $token): ?array
    {
        $raw = $this->base64UrlDecode($token);
        if ($raw === null) {
            return null;
        }

        $parts = explode(':', $raw, 3);
        if (count($parts) !== 3) {
            return null;
        }

        [$type, $id, $sig] = $parts;
        if ($type === '' || $id === '' || $sig === '') {
            return null;
        }

        $expected = hash_hmac('sha256', $type . ':' . $id, $this->secret);
        if (!hash_equals($expected, $sig)) {
            return null;
        }

        return [
            'type' => $type,
            'id' => $id,
        ];
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): ?string
    {
        $padded = strtr($value, '-_', '+/');
        $padding = strlen($padded) % 4;
        if ($padding > 0) {
            $padded .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode($padded, true);
        return $decoded === false ? null : $decoded;
    }
}
