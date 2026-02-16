<?php

namespace App\Middleware;

use App\Core\View;
use App\Core\Request;
use App\Core\Session;
use App\Entity\Student;
use App\Enum\UserStatus;
use App\Services\JwtService;
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
        private readonly JwtService $jwt,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->jwt->extractToken($request);
        if (empty($token)) {
            return $this->unauthorizedResponse($request);
        }

        $payload = $this->jwt->decode($token);
        if (!$payload || empty($payload['sub'])) {
            return $this->unauthorizedResponse($request);
        }

        // Get the student entity, If User is authenticated
        $student = $this->em->getRepository(Student::class)->findOneBy(
            ['user' => (int) $payload['sub']]
        );

        if (is_null($student)) {
            return $this->unauthorizedResponse($request);
        }

        if ($student->getUser()->getStatus() !== UserStatus::ACTIVE) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        // Set the user data and role in the request
        $request = $request->withAttribute('student', $student);

        return $handler->handle($request);
    }

    private function unauthorizedResponse(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->requestService->shouldStoreRedirect($request)) {
            $returnUrl = $this->requestService->normalizeRedirectUri($request->getUri());
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login', [], [
                    'returnUrl' => $returnUrl,
                ]));
        }

        return $this->responseFactory
            ->createResponse(401)
            ->withHeader('Content-Type', 'application/json');
    }
}
