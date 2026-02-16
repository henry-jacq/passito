<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Entity\Verifier;
use App\Enum\UserRole;
use App\Enum\VerifierMode;
use App\Enum\UserStatus;
use App\Services\OutpassService;
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
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly OutpassService $outpassService
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
                    if ($user->getStatus() !== UserStatus::ACTIVE) {
                        return $this->responseFactory
                            ->createResponse(302)
                            ->withHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
                            ->withHeader('Location', $this->view->urlFor('auth.login'));
                    }
                    $location = $this->view->urlFor('student.dashboard');

                    if (UserRole::isAdministrator($user->getRole()->value)) {
                        $location = $this->view->urlFor('admin.dashboard');
                    }
                    if (UserRole::isVerifier($user->getRole()->value)) {
                        $verifierMode = $this->outpassService->getVerifierMode();
                        $verifier = $this->em->getRepository(Verifier::class)->findOneBy([
                            'user' => $user,
                            'type' => VerifierMode::MANUAL,
                        ]);

                        $isActiveVerifier = $verifier && $user->getStatus() === UserStatus::ACTIVE;
                        $manualDisabled = $verifierMode === VerifierMode::AUTOMATED;

                        if (!$isActiveVerifier || $manualDisabled) {
                            return $this->responseFactory
                                ->createResponse(302)
                                ->withHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
                                ->withHeader('Location', $this->view->urlFor('auth.login', [], [
                                    'error' => 'verifier_inactive',
                                ]));
                        }

                        $location = $this->view->urlFor('verifier.dashboard');
                    }

                    return $this->responseFactory
                        ->createResponse(302)
                        ->withHeader('Location', $location);
                }
            }

            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }
               
        return $handler->handle($request);
    }
}
