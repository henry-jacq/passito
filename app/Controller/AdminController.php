<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly View $view,
    ) {
        parent::__construct($view);
    }

    public function home(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Admin Home'
        ];
        return $this->render($response, 'admin/home', $args, footer: true);
    }
}
