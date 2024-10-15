<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\AdminController;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Auth Routes
    $app->any('/login', [AuthController::class, 'login']);
    $app->any('/logout', [AuthController::class, 'logout']);

    // Admin Routes
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard'])->setName('admin.dashboard');
        $group->any('/settings', [AdminController::class, 'settings'])->setName('admin.settings');
        $group->any('/manage/requests', [AdminController::class, 'manageRequests'])->setName('admin.manage.requests');
    });

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    });
};
