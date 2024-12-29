<?php

namespace App\Controller;

use App\Core\View;
use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SetupController extends BaseController
{
    public function __construct(
        protected readonly View $view
    )
    {
    }
    
    public function install(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Setup Page'
        ];
        return parent::render($request, $response, 'setup/install', $args);
    }
}
