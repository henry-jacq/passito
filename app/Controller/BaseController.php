<?php

namespace App\Controller;

use App\Core\View;
use Slim\Psr7\Request;
use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;

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

    protected function getRouteName(Request $request): string
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        return $route ? $route->getName() : 'unknown_route';
    }
}
