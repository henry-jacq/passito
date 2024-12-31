<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController
{
    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Dashboard',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/dashboard', $args);
    }

    public function manageRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Manage Requests',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/manage_requests', $args);
    }

    public function pendingRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Pending Requests',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/pending_requests', $args);
    }

    public function outpassRecords(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Outpass Records',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/outpass_records', $args);
    }

    public function manageStudents(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Manage Students',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/students', $args);
    }
    
    public function manageWardens(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Manage Wardens',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/wardens', $args);
    }
    
    public function manageVerifiers(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Manage Verifiers',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/verifiers', $args);
    }

    public function manageLogbook(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Manage Logbook',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/logbook', $args);
    }
    
    public function settings(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Settings',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/settings', $args);
    }
}
