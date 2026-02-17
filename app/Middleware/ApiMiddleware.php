<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Entity\Student;
use App\Core\Request;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Services\JwtService;
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
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly JwtService $jwt,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        if (str_starts_with($path, '/api/verifiers')) {
            return $handler->handle($request);
        }

        $token = $this->jwt->extractToken($request);
        if (empty($token)) {
            return $handler->handle($request);
        }

        $payload = $this->jwt->decode($token);
        if (!$payload || empty($payload['sub'])) {
            if (str_starts_with($path, '/api/web/auth/')) {
                return $handler->handle($request);
            }
            $response = $handler->handle($request);
            return $response->withAddedHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader());
        }

        // Fetch user and role
        $user = $this->em->getRepository(User::class)->find((int) $payload['sub']);
        if (!$user) {
            if (str_starts_with($path, '/api/web/auth/')) {
                return $handler->handle($request);
            }
            $response = $handler->handle($request);
            return $response->withAddedHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader());
        }
        if ($user->getStatus() !== UserStatus::ACTIVE) {
            return $this->responseFactory
                ->createResponse(403)
                ->withHeader('Content-Type', 'application/json')
                ->withAddedHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
                ->withBody($this->streamFactory->createStream(json_encode([
                    'message' => 'Account inactive',
                    'status' => false,
                ])));
        }

        $userRole = $user->getRole()->value;

        if (UserRole::isStudent($userRole)) {
            $student = $this->em->getRepository(Student::class)->findOneBy(['user' => $user]);
            $academicYear = $student?->getAcademicYear();

            if (!$student || !$academicYear || !$academicYear->getStatus()) {
                return $this->responseFactory
                    ->createResponse(403)
                    ->withHeader('Content-Type', 'application/json')
                    ->withAddedHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
                    ->withBody($this->streamFactory->createStream(json_encode([
                        'message' => 'Academic year inactive',
                        'status' => false,
                    ])));
            }
        }

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

        // Prevent unauthorized access to verifier routes
        if (str_contains($request->getUri()->getPath(), '/api/web/verifier') && !UserRole::isVerifier($userRole)) {
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
