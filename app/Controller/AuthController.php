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
        $args = [
            'title' => 'Login'
        ];
        return parent::render($request, $response, 'auth/login', $args);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
