<?php

namespace App\Middleware;

use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly User $user,
        private readonly Session $session,
        private readonly Request $requestService,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {        
        if (empty($_SESSION['user'])) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', '/login');
            }
        } else {
            $user = $this->user->getUser();
            if ($user['role'] !== 'admin') {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', '/');
            }
            $request = $request->withAttribute('userData', $user);
            $request = $request->withAttribute('role', $user['role']);
        }

        return $handler->handle($request);
    }
}
