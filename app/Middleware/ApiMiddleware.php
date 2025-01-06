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
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class ApiMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if user is logged in
        if (empty($this->session->get('user'))) {
            return $handler->handle($request);
        }

        // Fetch user and role
        $user = $this->em->getRepository(User::class)->find((int) $this->session->get('user'));
        if (!$user) {
            return $this->responseFactory
                ->createResponse(403)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream(json_encode([
                    'message' => 'Forbidden',
                    'status' => false,
                ])));
        }

        $userRole = $user->getRole()->value;

        // Prevent unauthorized access to admin routes
        if (str_contains($request->getUri()->getPath(), '/api/web/admin') && UserRole::isAdministrator($userRole) === false) {
            return $this->responseFactory
                ->createResponse(403)
                ->withHeader('Content-Type', 'application/json')
                ->withBody($this->streamFactory->createStream(json_encode([
                    'message' => 'Forbidden',
                    'status' => false,
                ])));
        }

        // Set user in request
        $request = $request->withAttribute('user', $user);

        // Pass the request to the next middleware or handler
        return $handler->handle($request);
    }
}
