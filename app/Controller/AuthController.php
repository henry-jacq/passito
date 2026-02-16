<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Config;
use App\Services\AuthService;
use App\Services\JwtService;
use App\Services\LoginSessionService;
use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(
        protected readonly AuthService $auth,
        protected readonly Config $config,
        protected readonly View $view,
        protected readonly JwtService $jwt,
        protected readonly LoginSessionService $loginSessionService
    )
    {
    }
    
    public function login(Request $request, Response $response): Response
    {
        $errorCode = $request->getQueryParams()['error'] ?? null;
        $error = null;
        if ($errorCode === 'verifier_inactive') {
            $error = 'Verifier account is not active. Please contact the administrator.';
        }

        $args = [
            'title' => 'Login',
            'brandLogo' => $this->config->get('app.logo'),
            'error' => $error,
        ];
        return parent::render($request, $response, 'auth/login', $args);
    }

    public function forgot(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Forgot Password',
            'brandLogo' => $this->config->get('app.logo'),
        ];

        return parent::render($request, $response, 'auth/forgot', $args);
    }

    public function reset(Request $request, Response $response): Response
    {
        $token = (string) (($request->getQueryParams()['token'] ?? '') ?: '');
        $args = [
            'title' => 'Reset Password',
            'brandLogo' => $this->config->get('app.logo'),
            'token' => $token,
            'tokenMissing' => $token === '',
        ];

        return parent::render($request, $response, 'auth/reset', $args);
    }

    // function to landing page
    public function landing(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Passito - Seamless Gatepass Management System'
        ];
        return parent::render($request, $response, 'auth/landing', $args);
    }

    // public function authenticate(Request $request, Response $response): Response
    // {
    //     $data = $request->getParsedBody();
    //     $user = $this->auth->authenticate($data['email'], $data['password']);
    //     if ($user) {
    //         return $response->withHeader('Location', '/dashboard')->withStatus(302);
    //     }
    //     return $response->withHeader('Location', '/login')->withStatus(302);
    // }

    public function logout(Request $request, Response $response): Response
    {
        $token = $this->jwt->extractToken($request);
        if (!empty($token)) {
            $payload = $this->jwt->decode($token);
            $sessionTokenId = (string) ($payload['sid'] ?? '');
            if ($sessionTokenId !== '') {
                $this->loginSessionService->revokeByToken($sessionTokenId);
            }
        }

        $this->auth->logout();
        $loginPage = $this->view->urlFor('auth.login');
        return $response
            ->withHeader('Set-Cookie', $this->jwt->buildLogoutCookieHeader())
            ->withHeader('Location', $loginPage)
            ->withStatus(302);
    }
}
