<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Enum\UserRole;
use App\Services\JwtService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly JwtService $jwt,
        private readonly EntityManagerInterface $em,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->jwt->extractToken($request);
        if (!empty($token)) {
            $payload = $this->jwt->decode($token);
            if ($payload && !empty($payload['sub'])) {
                $user = $this->em->getRepository(User::class)->find((int) $payload['sub']);
                if ($user) {
                    $location = $this->view->urlFor('student.dashboard');

                    if (UserRole::isAdministrator($user->getRole()->value)) {
                        $location = $this->view->urlFor('admin.dashboard');
                    }

                    return $this->responseFactory
                        ->createResponse(302)
                        ->withHeader('Location', $location);
                }
            }
        }
               
        return $handler->handle($request);
    }
}
