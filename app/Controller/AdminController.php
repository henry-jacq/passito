<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController
{
    public function dashboard(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Dashboard',
        ];
        return parent::render($request, $response, 'admin/dashboard', $args);
    }

    public function manageRequests(Request $request, Response $response): Response
    {
        $args = [
            'title' => 'Manage Requests',
        ];
        return parent::render($request, $response, 'admin/manage_requests', $args);
    }
}
