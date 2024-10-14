<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Auth Routes
    $app->any('/login', [AuthController::class, 'login']);
    $app->any('/logout', [AuthController::class, 'logout']);

    // Admin Routes
    $app->group('/admin', function (RouteCollectorProxy $group) {
        $group->any('/dashboard', [HomeController::class, 'index'])->setName('admin.dashboard');
        $group->any('/manage/requests', [HomeController::class, 'manageRequests'])->setName('admin.manage.requests');
        $group->any('/manage/users', [HomeController::class, 'manageUsers'])->setName('admin.manage.users');
    });

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    });
};
