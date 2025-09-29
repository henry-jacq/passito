<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Config;
use App\Enum\UserRole;
use App\Services\AcademicService;
use App\Services\UserService;
use App\Services\AdminService;
use App\Services\OutpassService;
use App\Services\ReportService;
use App\Services\VerifierService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class AdminController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        protected readonly Config $config,
        private readonly UserService $userService,
        private readonly AdminService $adminService,
        private readonly ReportService $reportService,
        private readonly OutpassService $outpassService,
        private readonly VerifierService $verifierService,
        private readonly AcademicService $academicService
    )
    {
        $this->view->addGlobals('appName', $this->config->get('app.name'));
        $this->view->addGlobals('brandLogo', $this->config->get('app.logo'));
        $this->view->addGlobals('appVersion', $this->config->get('app.version'));
    }
    
    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $userData = $request->getAttribute('user');
        $dashboardData = $this->adminService->getDashboardDetails($userData);
        $lateArrivalsReport = $this->verifierService->fetchLateArrivals($userData);

        $args = [
            'title' => 'Dashboard',
            'user' => $userData,
            'data' => $dashboardData,
            'lateArrivals' => $lateArrivalsReport,
            'routeName' => $this->getRouteName($request),
        ];

        if ($userData->getRole() == UserRole::SUPER_ADMIN) {
            $reportSettings = $this->reportService->getAllReportSettings($userData);
            $args['reportSettings'] = $reportSettings;
        }

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/dashboard', $args);
    }

    public function pendingRequests(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $hostelFilter = $request->getQueryParams()['hostel'] ?? 'default';

        $wardenHostels = $userData->getHostels(); // Doctrine Collection
        $allHostels = iterator_to_array($this->academicService->getHostelsByType($userData));
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

        $pendingCount = count($this->outpassService->getPendingOutpass(
            paginate: false,
            warden: $userData
        ));

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
        return parent::render($request, $response, 'admin/pending', $args);
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
        return parent::render($request, $response, 'admin/records', $args);
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
        
        return parent::render($request, $response, 'admin/outpass', $args);
    }

    public function outpassSettings(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $data = $request->getParsedBody();
        $userData = $request->getAttribute('user');
        $args = [
            'user' => $userData,
            'title' => 'Firewall Rules',
            'routeName' => $this->getRouteName($request),
        ];

        if ($data != null) {
            $settings = $this->outpassService->updateSettings($userData, $data);
            $args['settings'] = $settings;
        } else {
            $settings = $this->outpassService->getSettings($userData->getGender());
            $args['settings'] = $settings;
        }
        
        return parent::render($request, $response, 'admin/rules', $args);
    }

    public function outpassTemplates(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $data = $request->getParsedBody();
        $userData = $request->getAttribute('user');
        $templates = $this->outpassService->getTemplates($userData, null);
        $args = [
            'user' => $userData,
            'title' => 'Template Builder',
            'routeName' => $this->getRouteName($request),
            'templates' => $templates,
        ];

        return parent::render($request, $response, 'admin/templates', $args);
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
    
    public function manageResidence(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $wardens = $this->userService->getWardensByGender($userData);
        $hostels = $this->academicService->getHostelsByType($userData);
        $assignments = $this->adminService->getAssignmentsByGender($userData);

        $args = [
            'title' => 'Manage Residence',
            'user' => $userData,
            'hostels' => $hostels,
            'wardens' => $wardens,
            'assignmentsView' => $assignments,
            'routeName' => $this->getRouteName($request),
        ];

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/residence', $args);
    }
    
    public function manageVerifiers(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $verifiers = $this->verifierService->getVerifiers();

        $args = [
            'title' => 'Verifier Control',
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
            'title' => 'Outpass Logbook',
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
    
    public function manageAcademics(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        $institutions = $this->academicService->getInstitutions();
        $programs = $this->academicService->getPrograms();

        $args = [
            'title' => 'Manage Academics',
            'user' => $userData,
            'programs' => $programs,
            'institutions' => $institutions,
            'routeName' => $this->getRouteName($request),
        ];

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'admin/academics', $args);
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
