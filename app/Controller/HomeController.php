<?php

namespace App\Controller;

use App\Core\View;
use App\Entity\Outpass;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Home'
        ];
        return parent::render($request, $response, 'user/test', $args);
    }
}
