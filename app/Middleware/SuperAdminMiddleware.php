<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Enum\UserRole;
use App\Services\JwtService;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class SuperAdminMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly JwtService $jwt,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->jwt->extractToken($request);
        if (empty($token)) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $this->view->urlFor('auth.login'));
            }

            return $handler->handle($request);
        }

        $payload = $this->jwt->decode($token);
        if (!$payload || empty($payload['sub'])) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        // User is authenticated
        $user = $this->em->getRepository(User::class)->find(
            (int) $payload['sub']
        );
        if (is_null($user)) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }
        $userRole = $user->getRole()->value;

        // Prevent unauthorized users from accessing super admin routes
        if (!UserRole::isSuperAdmin($userRole)) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        return $handler->handle($request);
    }
}
