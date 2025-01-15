<?php

namespace App\Controller;

use App\Core\View;
use App\Services\UserService;
use App\Services\FacilityService;
use App\Services\OutpassService;
use App\Services\VerifierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        private readonly UserService $userService,
        private readonly OutpassService $outpassService,
        private readonly VerifierService $verifierService,
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
        $outpasses = $this->outpassService->getPendingOutpass();

        $args = [
            'title' => 'Pending Requests',
            'user' => $userData,
            'outpasses' => $outpasses,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/pending_requests', $args);
    }

    public function outpassRecords(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $page = (int) ($request->getQueryParams()['page'] ?? 1);
        $limit = 10; // You can configure this value.

        // Handle invalid page numbers before proceeding with pagination query
        if ($page < 1) {
            $page = 1;
        }

        // Fetch the pagination data
        $paginationData = $this->outpassService->getOutpassRecords($page, $limit);

        // If page exceeds total pages, redirect to the last page
        if ($page > $paginationData['totalPages']) {
            return $response->withHeader('Location', '?page=' . $paginationData['totalPages'])->withStatus(302);
        }

        $args = [
            'title' => 'Outpass Records',
            'user' => $userData,
            'outpasses' => $paginationData['data'],
            'records' => [
                'currentPage' => $paginationData['currentPage'],
                'totalPages' => $paginationData['totalPages'],
                'totalRecords' => $paginationData['total'],
            ],
            'routeName' => $this->getRouteName($request),
        ];

        return parent::render($request, $response, 'admin/outpass_records', $args);
    }

    public function outpassSettings(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $args = [
            'title' => 'Outpass Settings',
            'user' => $userData,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/outpass_settings', $args);
    }

    public function manageStudents(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $students = $this->userService->getStudentsByGender($userData);

        $args = [
            'title' => 'Manage Students',
            'user' => $userData,
            'students' => $students,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/students', $args);
    }
    
    public function manageWardens(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $wardens = $this->userService->getWardensByGender($userData);

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
        $verifiers = $this->verifierService->getVerifiers();

        $args = [
            'title' => 'Manage Verifiers',
            'user' => $userData,
            'verifiers' => $verifiers,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/verifiers', $args);
    }

    public function manageLogbook(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $logbook = $this->verifierService->fetchAllLogs();

        $args = [
            'title' => 'Manage Logbook',
            'user' => $userData,
            'logbook' => $logbook,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'admin/logbook', $args);
    }
    
    public function manageFacilities(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $institutions = $this->facilityService->getInstitutions();
        $hostels = $this->facilityService->getHostelsByType($userData);

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
