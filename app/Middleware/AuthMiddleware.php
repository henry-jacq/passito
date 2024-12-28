<?php

namespace App\Middleware;

use App\Core\Session;
use App\Core\View;
use App\Enum\UserRole;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
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
        if (!empty($this->session->get('user'))) {

            $role = $this->session->get('role');
            $location = $this->view->urlFor('student.dashboard');
            
            if (UserRole::isAdministrator($role)) {
                $location = $this->view->urlFor('admin.dashboard');
            }

            return $this->responseFactory
            ->createResponse(302)
            ->withHeader('Location', $location);
        }
               
        return $handler->handle($request);
    }
}