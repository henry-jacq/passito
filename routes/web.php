<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\AdminController;
use App\Controller\StudentController;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Auth Routes
    $app->any('/login', [AuthController::class, 'login']);
    $app->any('/logout', [AuthController::class, 'logout']);

    // Student Routes
    $app->group('/student', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [StudentController::class, 'dashboard'])->setName('student.dashboard');
        $group->any('/outpass/request', [StudentController::class, 'requestOutpass'])->setName('student.outpass.request');
        $group->any('/outpass/status', [StudentController::class, 'statusOutpass'])->setName('student.outpass.status');
        $group->any('/outpass/history', [StudentController::class, 'outpassHistory'])->setName('student.outpass.history');
        $group->any('/profile', [StudentController::class, 'profile'])->setName('student.profile');
        $group->any('/logout', [StudentController::class, 'logout'])->setName('student.logout');
    });

    // Admin Routes
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard'])->setName('admin.dashboard');
        $group->any('/outpass/pending', [AdminController::class, 'pendingRequests'])->setName('admin.outpass.pending');
        $group->any('/outpass/records', [AdminController::class, 'outpassRecords'])->setName('admin.outpass.records');
        $group->any('/settings', [AdminController::class, 'settings'])->setName('admin.settings');
        $group->any('/manage/requests', [AdminController::class, 'manageRequests'])->setName('admin.manage.requests');
    });

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    });
};
