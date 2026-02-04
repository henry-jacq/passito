<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TrailingSlashMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/' && str_ends_with($path, '/')) {
            $normalizedPath = rtrim($path, '/');
            $normalizedUri = $uri->withPath($normalizedPath);

            $status = strtoupper($request->getMethod()) === 'GET' ? 301 : 307;

            return $this->responseFactory
                ->createResponse($status)
                ->withHeader('Location', (string) $normalizedUri);
        }

        return $handler->handle($request);
    }
}
