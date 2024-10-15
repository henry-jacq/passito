<?php

namespace App\Controller;

use App\Core\View;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;

class BaseController
{
    public function __construct(
        protected readonly View $view
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

    public function render(Request $request, Response $response, string $viewPath, array $args)
    {
        $response->getBody()->write(
            (string) $this->view
                ->createPage($viewPath, $args)
                ->render()
        );

        return $response;
    }

    /**
     * Redirect response
     */
    public function redirect(Response $response, string $location, int $status = 302): Response
    {
        return $response->withHeader('Location', $location)->withStatus($status);
    }

    public function addGlobals(string $key, string $value)
    {
        $this->view->addGlobals($key, $value);
    }
}
