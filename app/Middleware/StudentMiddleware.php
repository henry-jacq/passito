<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Enum\UserRole;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class StudentMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
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
            $user = $this->em->getRepository(User::class)->find(
                (int) $this->session->get('user')
            );

            // Prevent unauthorized users from accessing student routes
            if (!UserRole::isStudent($user->getRole())) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->view->urlFor('auth.login'));
            }

            // Prevent administrators from accessing student routes
            if (UserRole::isAdministrator($user->getRole()) === true) {
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