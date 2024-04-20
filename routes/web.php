<?php

use Slim\App;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\AdminController;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->any('/', [HomeController::class, 'index']);
    $app->any('/request', [HomeController::class, 'request']);
    $app->any('/login', [AuthController::class, 'login']);

    $app->group('/admin', function(RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard']);
        $group->any('/manage/request', [AdminController::class, 'manageRequests']);
        $group->any('/manage/users', [AdminController::class, 'manageUsers']);
        $group->any('/analytics', [AdminController::class, 'analytics']);
        $group->any('/create/announcements', [AdminController::class, 'announcements']);
        $group->any('/settings', [AdminController::class, 'settings']);
    });
};
