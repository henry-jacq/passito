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

    public function pendingRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Pending Requests',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/pending_requests', $args);
    }

    public function outpassRecords(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Outpass Records',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/outpass_records', $args);
    }
    
    public function manageVerifiers(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Manage Verifiers',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/verifiers', $args);
    }

    public function manageLogbook(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Manage Logbook',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/logbook', $args);
    }
    
    public function settings(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $args = [
            'title' => 'Settings',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/settings', $args);
    }
}
