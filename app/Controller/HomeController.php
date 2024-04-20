<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view,
    ) {
        parent::__construct($view);
    }

    public function index(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home'
        ];
        return $this->render($request, $response, 'user/home', $args, footer: true);
    }

    public function request(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Request'
        ];
        return $this->render($request, $response, 'user/request', $args, footer: true);
    }

}
