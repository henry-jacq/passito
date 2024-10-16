<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory)
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!empty($_SESSION['user'])) {
            $location = "/";
            if ($request->getAttribute('role') == 'admin') {
                $location = "/admin/dashboard";
            }
            return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $location);
        }
        
        $request->withAttribute('role', 'guest');
        
        return $handler->handle($request);
    }
}