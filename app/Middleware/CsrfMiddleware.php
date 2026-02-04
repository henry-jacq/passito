<?php

namespace App\Middleware;

use App\Core\Config;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{
    private const UNSAFE_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function __construct(
        private readonly Config $config,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->isUnsafeMethod($request)) {
            $authorization = $request->getHeaderLine('Authorization');
            if (empty($authorization)) {
                if (!$this->hasValidOrigin($request)) {
                    return $this->reject($request, 'Invalid origin');
                }

                if (!$this->hasValidCsrfToken($request)) {
                    return $this->reject($request, 'Invalid CSRF token');
                }
            }
        }

        $response = $handler->handle($request);

        return $this->ensureCsrfCookie($request, $response);
    }

    private function isUnsafeMethod(ServerRequestInterface $request): bool
    {
        return in_array(strtoupper($request->getMethod()), self::UNSAFE_METHODS, true);
    }

    private function hasValidOrigin(ServerRequestInterface $request): bool
    {
        $origin = $request->getHeaderLine('Origin');
        $referer = $request->getHeaderLine('Referer');

        $allowedHost = parse_url((string) $this->config->get('app.host'), PHP_URL_HOST);
        if (empty($allowedHost)) {
            return true;
        }

        if (!empty($origin)) {
            $originHost = parse_url($origin, PHP_URL_HOST);
            return $originHost === $allowedHost;
        }

        if (!empty($referer)) {
            $refererHost = parse_url($referer, PHP_URL_HOST);
            return $refererHost === $allowedHost;
        }

        return false;
    }

    private function hasValidCsrfToken(ServerRequestInterface $request): bool
    {
        $cookieName = $this->config->get('csrf.cookie.name', 'passito_csrf');
        $headerName = $this->config->get('csrf.header', 'X-CSRF-Token');

        $cookies = $request->getCookieParams();
        $cookieToken = $cookies[$cookieName] ?? null;
        $headerToken = $request->getHeaderLine($headerName);

        if (empty($cookieToken) || empty($headerToken)) {
            return false;
        }

        return hash_equals($cookieToken, $headerToken);
    }

    private function ensureCsrfCookie(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cookieName = $this->config->get('csrf.cookie.name', 'passito_csrf');
        $cookies = $request->getCookieParams();

        if (!empty($cookies[$cookieName])) {
            return $response;
        }

        $token = bin2hex(random_bytes(32));

        return $response->withHeader('Set-Cookie', $this->buildCookieHeader($token));
    }

    private function buildCookieHeader(string $value): string
    {
        $cookie = $this->config->get('csrf.cookie', []);
        $name = $cookie['name'] ?? 'passito_csrf';
        $path = $cookie['path'] ?? '/';
        $secure = !empty($cookie['secure']);
        $httpOnly = !empty($cookie['httponly']);
        $sameSite = $cookie['samesite'] ?? 'lax';

        $parts = [];
        $parts[] = sprintf('%s=%s', $name, $value);
        $parts[] = 'Path=' . $path;

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

    private function reject(ServerRequestInterface $request, string $message): ResponseInterface
    {
        $isApi = str_starts_with($request->getUri()->getPath(), '/api');
        $response = $this->responseFactory->createResponse(403);

        if ($isApi) {
            $payload = json_encode([
                'message' => $message,
                'status' => false,
            ]);

            $response = $response
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream($payload));

            return $this->ensureCsrfCookie($request, $response);
        }

        $response->getBody()->write($message);

        return $this->ensureCsrfCookie($request, $response);
    }
}
