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
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
            }
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        $user = $this->em->getRepository(User::class)->find((int) $payload['sub']);
        if (is_null($user) || !UserRole::isVerifier($user->getRole()->value)) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
            }
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        $verifier = $this->verifierService->getVerifierByUser($user);
        if (!$verifier || !$this->verifierService->isActiveVerifier($verifier)) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
            }
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        $settings = $this->outpassService->getSettings($user->getGender());
        $verifierMode = $settings?->getVerifierMode();
        if ($verifierMode === VerifierMode::AUTOMATED) {
            if ($request->getMethod() === 'GET' && !$this->requestService->isXhr($request)) {
                $this->session->put('_redirect', (string) $request->getUri());
            }
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
