<?php

namespace App\Core;

use Slim\Psr7\Request;
use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    public function __construct(
        private readonly View $view
    )
    {
    }

    public function renderErrorPage(Response $response, $params = [])
    {
        $response->getBody()->write(
            (string) $this->view
                ->createPage('error', $params)
                ->render()
        );
        return $response->withStatus($params['code']);
    }
    
    public function render(Request $request, Response $response, string $viewPath, array $args, $header = true, $footer = false)
    {
        $role = $request->getAttribute('role');

        if ($role == "admin") {
            $args['role'] = $role;
        } else {
            $args['header'] = $header;
            $args['footer'] = $footer;
        }

        $response->getBody()->write(
            (string) $this->view
                ->createPage($viewPath, $args)
                ->render()
        );

        return $response;
    }
}