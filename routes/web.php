<?php

use Slim\App;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\AdminController;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->any('/', [HomeController::class, 'index']);
    $app->any('/login', [AuthController::class, 'login']);

    $app->group('/admin', function(RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard']);
        $group->any('/manage/request', [AdminController::class, 'dashboard']);
        $group->any('/manage/users', [AdminController::class, 'dashboard']);
        $group->any('/analytics', [AdminController::class, 'dashboard']);
        $group->any('/settings', [AdminController::class, 'dashboard']);
    });
};
