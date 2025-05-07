<?php

use Slim\App;
use App\Controller\ApiController;
use App\Middleware\ApiMiddleware;
use App\Controller\AuthController;
use App\Middleware\AuthMiddleware;
use App\Controller\AdminController;
use App\Controller\SetupController;
use App\Middleware\AdminMiddleware;
use App\Controller\ParentController;
use App\Controller\StorageController;
use App\Controller\StudentController;
use App\Middleware\StudentMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\SuperAdminMiddleware;

return function (App $app) {
    $app->get('/', [AuthController::class, 'landing'])->setName('landing')->add(AuthMiddleware::class);
    
    // Auth Routes
    $app->group('/auth', function (RouteCollectorProxy $group) {
        $group->any('/login', [AuthController::class, 'login'])->setName('auth.login')->add(AuthMiddleware::class);
        $group->any('/logout', [AuthController::class, 'logout'])->setName('auth.logout');
    });

    // Setup wizard routes
    $app->group('/setup', function (RouteCollectorProxy $group) {
        $group->any('/install', [SetupController::class, 'install'])->setName('setup.install');
        $group->any('/update', [SetupController::class, 'update'])->setName('setup.update');
    });

    // Student Routes
    $app->group('/student', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [StudentController::class, 'dashboard'])->setName('student.dashboard');
        $group->any('/outpass/request', [StudentController::class, 'requestOutpass'])->setName('student.outpass.request');
        $group->any('/outpass/status', [StudentController::class, 'statusOutpass'])->setName('student.outpass.status');
        $group->any('/outpass/status/{outpass_id}', [StudentController::class, 'outpassDetails'])->setName('student.outpass.details');
        $group->any('/outpass/history', [StudentController::class, 'outpassHistory'])->setName('student.outpass.history');
        $group->any('/profile', [StudentController::class, 'profile'])->setName('student.profile');
    })->add(StudentMiddleware::class);
    
    // Storage Routes
    $app->any('/storage/admin/{id}[/{params:.*}]', [StorageController::class, 'admin'])->setName('storage.admin')->add(AdminMiddleware::class);
    $app->any('/storage/student/{id}[/{params:.*}]', [StorageController::class, 'student'])->setName('storage.student')->add(StudentMiddleware::class);

    // Parent Verification Routes
    $app->group('/parent', function (RouteCollectorProxy $group) {
        $group->any('/verify', [ParentController::class, 'verify'])->setName('parent.verify');
    });
    
    // Admin Routes
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard'])->setName('admin.dashboard');
        $group->any('/outpass/pending', [AdminController::class, 'pendingRequests'])->setName('admin.outpass.pending');
        $group->any('/outpass/records', [AdminController::class, 'outpassRecords'])->setName('admin.outpass.records');
        $group->any('/outpass/records/{outpass_id:[0-9]+}', [AdminController::class, 'outpassDetails'])->setName('admin.outpass.records.details');
        $group->any('/manage/students', [AdminController::class, 'manageStudents'])->setName('admin.manage.students');
        $group->any('/outpass/settings', [AdminController::class, 'outpassSettings'])->setName('admin.outpass.settings');
        $group->any('/manage/wardens', [AdminController::class, 'manageWardens'])->setName('admin.manage.wardens')->add(SuperAdminMiddleware::class);
        $group->any('/manage/facilities', [AdminController::class, 'manageFacilities'])->setName('admin.manage.facilities')->add(SuperAdminMiddleware::class);
        $group->any('/manage/verifiers', [AdminController::class, 'manageVerifiers'])->setName('admin.manage.verifiers')->add(SuperAdminMiddleware::class);
        $group->any('/manage/logbook', [AdminController::class, 'manageLogbook'])->setName('admin.manage.logbook');
        $group->any('/outpass/templates', [AdminController::class, 'outpassTemplates'])->setName('admin.outpass.templates')->add(SuperAdminMiddleware::class);
        $group->any('/settings', [AdminController::class, 'settings'])->setName('admin.settings');
    })->add(AdminMiddleware::class);

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    })->add(ApiMiddleware::class);
};
