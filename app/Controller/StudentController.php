<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StudentController extends BaseController
{
    public function dashboard(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        $args = [
            'title' => 'Dashboard',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/dashboard', $args);
    }

    public function requestOutpass(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        $args = [
            'title' => 'Outpass Requisition',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/request_outpass', $args);
    }

    public function statusOutpass(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        $args = [
            'title' => 'Outpass Status',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/outpass_status', $args);
    }

    public function outpassHistory(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        $args = [
            'title' => 'Outpass History',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/outpass_history', $args);
    }

    public function profile(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        $args = [
            'title' => 'Profile',
            'routeName' => $this->getRouteName($request),
        ];
        return parent::render($request, $response, 'user/profile', $args);
    }

    public function logout(Request $request, Response $response): Response
    {
        $_SESSION['role'] = 'student';
        unset($_SESSION);
        return $response->withHeader('Location', '/');
    }
}