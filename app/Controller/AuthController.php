<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly View $view,
    ) {
        parent::__construct($view);
    }

    public function login(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Login'
        ];
        return $this->render($request, $response, 'auth/login', $args, header: false);
    }
}
