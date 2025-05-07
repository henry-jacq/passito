<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Config;
use App\Services\UserService;
use App\Services\AdminService;
use App\Services\OutpassService;
use App\Services\FacilityService;
use App\Services\VerifierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function PHPSTORM_META\type;

class AdminController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        protected readonly Config $config,
        private readonly UserService $userService,
        private readonly OutpassService $outpassService,
        private readonly AdminService $adminService,
        private readonly VerifierService $verifierService,
        private readonly FacilityService $facilityService
    )
    {
        $this->view->addGlobals('brandLogo', $this->config->get('app.logo'));
    }
    
    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $userData = $request->getAttribute('user');
        $dashboardData = $this->adminService->getDashboardDetails($userData);

        $args = [
            'title' => 'Dashboard',
            'user' => $userData,
            'data' => $dashboardData,
            'routeName' => $this->getRouteName($request),
        ];

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/dashboard', $args);
    }

    public function pendingRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $hostelFilter = $request->getQueryParams()['hostel'] ?? 'default';

        $wardenHostels = $userData->getHostels(); // Doctrine Collection
        $allHostels = iterator_to_array($this->facilityService->getHostelsByType($userData));
        $wardenHostelsArray = $wardenHostels->toArray();

        // Compute the difference (hostels not assigned to warden)
        $unassignedHostels = array_udiff($allHostels, $wardenHostelsArray, function ($a, $b) {
            return $a->getId() <=> $b->getId();
        });

        $page = max(1, (int) ($request->getQueryParams()['page'] ?? 1));
        $limit = 10;

        // Fetch the pagination data
        $paginationData = $this->outpassService->getPendingOutpass(
            page: $page,
            limit: $limit,
            paginate: true,
            warden: $userData,
            hostelFilter: $hostelFilter
        );

        $pendingCount = count($this->outpassService->getPendingOutpass(paginate: false));

        // Redirect to the last page if the requested page exceeds available pages
        if ($paginationData['totalPages'] > 1 && $page > $paginationData['totalPages']) {
            return $response->withHeader('Location', '?page=' . $paginationData['totalPages'])->withStatus(302);
        }

        $args = [
            'title' => 'Pending Requests',
            'user' => $userData,
            'outpasses' => $paginationData['data'],
            'records' => [
                'currentPage' => $paginationData['currentPage'],
                'totalPages' => $paginationData['totalPages'],
                'totalRecords' => $paginationData['total'],
            ],
            'routeName' => $this->getRouteName($request),
            'pendingCount' => $pendingCount,
            'hostelFilter' => $hostelFilter,
            'unassignedHostels' => $unassignedHostels
        ];

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/pending_requests', $args);
    }

    public function outpassRecords(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $page = max(1, (int) ($request->getQueryParams()['page'] ?? 1));
        $limit = 10;

        // Fetch the pagination data
        $paginationData = $this->outpassService->getOutpassRecords($page, $limit, $userData);

        // Redirect to the last page if the requested page exceeds available pages
        if ($paginationData['totalPages'] > 1 && $page > $paginationData['totalPages']) {
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

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/outpass_records', $args);
    }

    public function outpassDetails(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $outpassId = $request->getAttribute('outpass_id');
        $outpass = $this->outpassService->getOutpassById($outpassId);
        
        $args = [
            'title' => 'Outpass Details',
            'user' => $userData,
            'outpass' => $outpass,
            'outpass_id' => $outpassId,
            'routeName' => $this->getRouteName($request),
        ];
        
        return parent::render($request, $response, 'admin/outpass_details', $args);
    }

    public function outpassSettings(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $data = $request->getParsedBody();
        $userData = $request->getAttribute('user');
        $args = [
            'user' => $userData,
            'title' => 'Outpass Settings',
            'routeName' => $this->getRouteName($request),
        ];

        if ($data != null) {
            $settings = $this->outpassService->updateSettings($userData, $data);
            $args['settings'] = $settings;
        } else {
            $settings = $this->outpassService->getSettings($userData->getGender());
            $args['settings'] = $settings;
        }
        
        return parent::render($request, $response, 'admin/outpass_settings', $args);
    }

    public function outpassTemplates(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $data = $request->getParsedBody();
        $userData = $request->getAttribute('user');
        $args = [
            'user' => $userData,
            'title' => 'Outpass Settings',
            'routeName' => $this->getRouteName($request),
        ];

        if ($data != null) {
            $settings = $this->outpassService->updateSettings($userData, $data);
            $args['settings'] = $settings;
        } else {
            $settings = $this->outpassService->getSettings($userData->getGender());
            $args['settings'] = $settings;
        }

        return parent::render($request, $response, 'admin/outpass_templates', $args);
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

        $args = array_merge($args, $this->view->getGlobals());
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

        $args = array_merge($args, $this->view->getGlobals());
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

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/verifiers', $args);
    }

    public function manageLogbook(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $page = max(1, (int) ($request->getQueryParams()['page'] ?? 1));
        $limit = 10;

        // Fetch the pagination data
        $paginationData = $this->verifierService->fetchLogsByGender(
            user: $userData,
            page: $page,
            limit: $limit,
            paginate: true
        );

        // Redirect to the last page if the requested page exceeds available pages
        if ($paginationData['totalPages'] > 1 && $page > $paginationData['totalPages']) {
            return $response->withHeader('Location', '?page=' . $paginationData['totalPages'])->withStatus(302);
        }

        $args = [
            'title' => 'Manage Logbook',
            'user' => $userData,
            'logbook' => $paginationData['data'],
            'records' => [
                'currentPage' => $paginationData['currentPage'],
                'totalPages' => $paginationData['totalPages'],
                'totalRecords' => $paginationData['total'],
            ],
            'routeName' => $this->getRouteName($request),
        ];

        $args = array_merge($args, $this->view->getGlobals());
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

        $args = array_merge($args, $this->view->getGlobals());
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

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/settings', $args);
    }
}
