<?php

namespace App\Controller;

use App\Core\View;
use App\Services\FacilityService;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        private readonly UserService $userService,
        private readonly FacilityService $facilityService
    )
    {
    }
    
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
        $wardens = $this->userService->getWardens();

        $args = [
            'title' => 'Manage Wardens',
            'user' => $userData,
            'wardens' => $wardens,
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
    
    public function manageFacilities(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $institutions = $this->facilityService->getInstitutions();
        $hostels = $this->facilityService->getHostels();

        $args = [
            'title' => 'Manage Facilities',
            'user' => $userData,
            'hostels' => $hostels,
            'institutions' => $institutions,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/facilities', $args);
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
