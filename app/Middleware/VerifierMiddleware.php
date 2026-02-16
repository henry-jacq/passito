<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Core\Request;
use App\Core\Session;
use App\Enum\UserRole;
use App\Enum\VerifierMode;
use App\Services\JwtService;
use App\Services\VerifierService;
use App\Services\OutpassService;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class VerifierMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Session $session,
        private readonly View $view,
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly JwtService $jwt,
        private readonly VerifierService $verifierService,
        private readonly OutpassService $outpassService,
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

        $user = $this->em->getRepository(User::class)->find((int) $payload['sub']);
        if (is_null($user) || !UserRole::isVerifier($user->getRole()->value)) {
            return $this->unauthorizedResponse($request);
        }

        $verifier = $this->verifierService->getVerifierByUser($user);
        if (!$verifier || !$this->verifierService->isActiveVerifier($verifier)) {
            return $this->unauthorizedResponse($request);
        }

        $verifierMode = $this->outpassService->getVerifierMode();
        if ($verifierMode === VerifierMode::AUTOMATED) {
            return $this->unauthorizedResponse($request);
        }

        $request = $request->withAttribute('user', $user);

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
