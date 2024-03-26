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
        $args['header'] = $header;
        $args['footer'] = $footer;
        $role = $request->getAttribute('role');

        dd($role);
        
        if ($role == "admin") {
            $response->getBody()->write(
                (string) $this->view
                    ->createPage($viewPath, $args)
                    ->render()
            );
        } else {
            $response->getBody()->write(
                (string) $this->view
                    ->createPage($viewPath, $args)
                    ->render()
            );
        }
        return $response;
    }
}