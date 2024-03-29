<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly View $view,
    ) {
        parent::__construct($view);
    }

    public function dashboard(Request $request, Response $response): Response
    {
        
        $args = [
            'title' => 'Dashboard'
        ];
        return $this->render($request, $response, 'admin/dashboard', $args, header: false);
    }

    public function manageRequests(Request $request, Response $response): Response
    {

        $args = [
            'title' => 'Manage Requests'
        ];
        return $this->render($request, $response, 'admin/requests', $args, header: false);
    }

    public function manageUsers(Request $request, Response $response): Response
    {

        $args = [
            'title' => 'Manage Users'
        ];
        return $this->render($request, $response, 'admin/dashboard', $args, header: false);
    }

    public function analytics(Request $request, Response $response): Response
    {

        $args = [
            'title' => 'Analytics'
        ];
        return $this->render($request, $response, 'admin/dashboard', $args, header: false);
    }

    public function announcements(Request $request, Response $response): Response
    {

        $args = [
            'title' => 'Announcements'
        ];
        return $this->render($request, $response, 'admin/announcements', $args, header: false);
    }
    
    public function settings(Request $request, Response $response): Response
    {

        $args = [
            'title' => 'Settings'
        ];
        return $this->render($request, $response, 'admin/settings', $args, header: false);
    }
}
