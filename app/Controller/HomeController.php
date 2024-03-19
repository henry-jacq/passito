<?php

namespace App\Controller;

use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function home(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home'
        ];
        
        return $this->render($response, 'test', $args);
    }
}