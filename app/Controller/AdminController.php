<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController
{
    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Dashboard',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/dashboard', $args);
    }

    public function manageRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Manage Requests',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/manage_requests', $args);
    }
}
