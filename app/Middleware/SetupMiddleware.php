<?php

namespace App\Middleware;

use App\Core\View;
use App\Core\Session;
use App\Entity\User;
use App\Enum\UserRole;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetupMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // User must logged in and must be a super admin
        if (!empty($this->session->get('user')) && $this->session->get('role') === UserRole::SUPER_ADMIN->value) {
            return $handler->handle($request);
        }

        // TODO: Prevent if setup is already done
        
        return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $this->view->urlFor('auth.login'));
    }
}