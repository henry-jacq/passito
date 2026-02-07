<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Config;
use App\Services\AdminService;
use App\Services\OutpassService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StudentController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        protected readonly Config $config,
        private readonly AdminService $adminService,
        private readonly OutpassService $outpassService,
    )
    {
        $this->view->addGlobals('brandLogo', $this->config->get('app.logo'));
    }
    
    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $userData = $request->getAttribute('student');
        $recentOutpasses = $this->outpassService->getRecentStudentOutpass($userData, 3);
        $outpassStats = $this->outpassService->getStudentOutpassStats($userData);

        $args = [
            'title' => 'Dashboard',
            'userData' => $userData,
            'recentOutpasses' => $recentOutpasses,
            'outpassStats' => $outpassStats,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/dashboard', $args);
    }

    public function requestOutpass(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $userData = $request->getAttribute('student');
        $passType = $request->getQueryParams()['type'] ?? null;
        $lockStatus = $this->adminService->isRequestLock($userData->getUser()->getGender()->value);

        // It may return one template or all templates as array based on the $passType
        $templates = $this->outpassService->getTemplates($userData->getUser(), $passType);
        
        $args = [
            'title' => 'Outpass Request',
            'passType' => $passType,
            'routeName' => $this->getRouteName($request),
            'templates' => $templates,
            'lockStatus' => $lockStatus,
        ];
        return parent::render($request, $response, 'user/request_outpass', $args);
    }

    public function statusOutpass(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $student = $request->getAttribute('student');
        $outpass = $this->outpassService->getRecentStudentOutpass($student, 4);
        
        $args = [
            'title' => 'Outpass Status',
            'outpasses' => $outpass,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/outpass_status', $args);
    }

    public function outpassDetails(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $outpassId = $request->getAttribute('outpass_id');
        $outpass = $this->outpassService->getOutpassById($outpassId);

        if (!$outpass) {
            return $this->renderErrorPage($response, [
                'code' => 404,
                'message' => 'Outpass not found.'
            ]);
        }
        
        $args = [
            'title' => 'Outpass Details',
            'outpass' => $outpass,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/outpass_details', $args);
    }

    public function outpassHistory(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $student = $request->getAttribute('student');
        $page = (int)($request->getQueryParams()['page'] ?? 1);
        $outpassHistory = $this->outpassService->getOutpassHistoryByStudent($student, $page, 10);

        $args = [
            'title' => 'Outpass History',
            'outpasses' => $outpassHistory['data'],
            'currentPage' => $outpassHistory['currentPage'],
            'totalPages' => $outpassHistory['totalPages'],
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/outpass_history', $args);
    }

    public function profile(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();
        $userData = $request->getAttribute('student');
        $assignedWarden = null;
        $hostel = $userData?->getHostel();
        if ($hostel) {
            $assignedWarden = $this->adminService->getAssignedWardenForHostel($hostel->getId());
        }
        $args = [
            'title' => 'Profile',
            'userData' => $userData,
            'assignedWarden' => $assignedWarden,
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/profile', $args);
    }

}
