<?php

namespace App\Core;

use Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    public function __construct(
        private readonly View $view
    ) {
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

    public function render(Response $response, string $viewPath, array $args, $header = true, $footer = false)
    {
        $args['header'] = $header;
        $args['footer'] = $footer;
        $response->getBody()->write(
            (string) $this->view
                ->createPage($viewPath, $args)
                ->render()
        );
        return $response;
    }
}
