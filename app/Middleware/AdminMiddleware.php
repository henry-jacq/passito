<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Enum\UserRole;
use App\Entity\Settings;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class AdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Session $session,
        private readonly View $view,
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // User is not authenticated
        if (empty($this->session->get('user'))) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->view->urlFor('auth.login'));
            }
        } else { // User is authenticated
            // Get the user by id
            $user = $this->em->getRepository(User::class)->find((int) $this->session->get('user'));
            $userRole = $user->getRole()->value;

            // Prevent unauthorized users from accessing admin routes
            if (UserRole::isStudent($userRole)) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->view->urlFor('auth.login'));
            }
                        
            // Prevent students from accessing admin routes
            if (UserRole::isAdministrator($userRole) === false) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->view->urlFor('auth.login'));
            }

            // Set the user data and role in the request
            $request = $request->withAttribute('user', $user);
        }

        return $handler->handle($request);
    }
}
