<?php

namespace App\Services;

use App\Core\Config;
use App\Entity\User;
use App\Enum\UserRole;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ServerRequestInterface;
use UnexpectedValueException;

class JwtService
{
    private const ALG = 'HS256';
    private const LEEWAY = 5;

    public function __construct(
        private readonly Config $config,
        private readonly LoginSessionService $loginSessionService
    )
    {
    }

    public function createToken(User $user, ?string $sessionTokenId = null): string
    {
        $now = time();
        $ttl = (int) $this->config->get('jwt.ttl', 3600);

        $payload = [
            'iss' => $this->config->get('jwt.issuer'),
            'aud' => $this->config->get('jwt.audience'),
            'iat' => $now,
            'nbf' => $now - self::LEEWAY,
            'exp' => $now + $ttl,
            'sub' => (string) $user->getId(),
            'role' => $user->getRole()->value,
            'email' => $user->getEmail(),
        ];

        if (!empty($sessionTokenId)) {
            $payload['sid'] = $sessionTokenId;
        }

        return $this->encode($payload);
    }

    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->getSecret(), self::ALG);
    }

    public function decode(string $token): ?array
    {
        try {
            JWT::$leeway = self::LEEWAY;
            $decoded = JWT::decode($token, new Key($this->getSecret(), self::ALG));
        } catch (SignatureInvalidException | UnexpectedValueException $e) {
            return null;
        }

        $payload = json_decode(json_encode($decoded), true);
        if (!is_array($payload)) {
            return null;
        }

        if (!$this->isValidClaims($payload)) {
            return null;
        }

        if (!$this->isValidSession($payload)) {
            return null;
        }

        return $payload;
    }

    public function extractToken(ServerRequestInterface $request): ?string
    {
        $header = $request->getHeaderLine('Authorization');
        if (!empty($header)) {
            if (preg_match('/^Bearer\s+(\S+)$/i', $header, $matches)) {
                return $matches[1];
            }
        }

        $cookieName = $this->getCookieName();
        $cookies = $request->getCookieParams();

        return $cookies[$cookieName] ?? null;
    }

    public function buildAuthCookieHeader(string $token): string
    {
        $ttl = (int) $this->config->get('jwt.ttl', 3600);
        $expires = time() + $ttl;

        return $this->buildCookieHeader($token, $expires);
    }

    public function buildLogoutCookieHeader(): string
    {
        return $this->buildCookieHeader('', time() - 3600);
    }

    private function buildCookieHeader(string $value, int $expires): string
    {
        $cookie = $this->config->get('jwt.cookie', []);
        $name = $cookie['name'] ?? $this->getCookieName();
        $path = $cookie['path'] ?? '/';
        $secure = !empty($cookie['secure']);
        $httpOnly = !empty($cookie['httponly']);
        $sameSite = $cookie['samesite'] ?? 'lax';

        $parts = [];
        $parts[] = sprintf('%s=%s', $name, $value);
        $parts[] = 'Path=' . $path;
        $parts[] = 'Expires=' . gmdate('D, d M Y H:i:s T', $expires);

        if ($secure) {
            $parts[] = 'Secure';
        }

        if ($httpOnly) {
            $parts[] = 'HttpOnly';
        }

        if (!empty($sameSite)) {
            $parts[] = 'SameSite=' . ucfirst(strtolower($sameSite));
        }

        return implode('; ', $parts);
    }

    public function getCookieName(): string
    {
        return $this->config->get('jwt.cookie.name', 'passito_token');
    }

    private function getSecret(): string
    {
        $secret = (string) $this->config->get('jwt.secret');
        if ($secret === '') {
            return 'change-me';
        }

        return $secret;
    }

    private function isValidClaims(array $payload): bool
    {
        $issuer = $this->config->get('jwt.issuer');
        if (!empty($issuer) && ($payload['iss'] ?? null) !== $issuer) {
            return false;
        }

        $audience = $this->config->get('jwt.audience');
        if (!empty($audience) && ($payload['aud'] ?? null) !== $audience) {
            return false;
        }

        return true;
    }

    private function isValidSession(array $payload): bool
    {
        $role = (string) ($payload['role'] ?? '');
        if (!UserRole::isAdministrator($role)) {
            return true;
        }

        $tokenId = (string) ($payload['sid'] ?? '');
        if ($tokenId === '') {
            // Backward-compatible path: admin tokens without sid remain valid.
            // New logins include sid and are managed through login_sessions.
            return true;
        }

        try {
            return $this->loginSessionService->getActiveSessionByToken($tokenId) !== null;
        } catch (\Throwable) {
            // If session storage is temporarily unavailable, avoid blocking all admin logins.
            return true;
        }
    }
}
