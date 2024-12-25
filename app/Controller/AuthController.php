<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(
        protected readonly Auth $auth,
        protected readonly View $view
    )
    {
    }
    
    public function login(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'guest';
        $args = [
            'title' => 'Login'
        ];
        return parent::render($request, $response, 'auth/login', $args);
    }

    // function to landing page
    public function landing(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'guest';
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
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
