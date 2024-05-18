<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Middleware\AuthMiddleware;
use App\Controller\AdminController;
use App\Middleware\AdminMiddleware;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\AuthoriseMiddleware;

return function (App $app) {
    $app->any('/login', [AuthController::class, 'login'])->add(AuthMiddleware::class);
    $app->any('/logout', [AuthController::class, 'logout']);
    
    $app->group('/', function(RouteCollectorProxy $group) {
        $group->any('', [HomeController::class, 'index']);
        $group->any('pass/request', [HomeController::class, 'request']);
        $group->any('pass/status', [HomeController::class, 'status']);
        $group->any('my/inbox', [HomeController::class, 'inbox']);
    })->add(AuthoriseMiddleware::class);

    $app->group('/admin', function(RouteCollectorProxy $group) {
        $group->any('/dashboard', [AdminController::class, 'dashboard']);
        $group->any('/manage/request', [AdminController::class, 'manageRequests']);
        $group->any('/manage/users', [AdminController::class, 'manageUsers']);
        $group->any('/compose', [AdminController::class, 'composeMail']);
        $group->any('/analytics', [AdminController::class, 'analytics']);
        $group->any('/create/announcements', [AdminController::class, 'announcements']);
        $group->any('/settings', [AdminController::class, 'settings']);
        $group->any('/logout', [AdminController::class, 'logout']);
    })->add(AdminMiddleware::class);

    // API Routes
    $app->group('/api', function (RouteCollectorProxy $group) {
        $group->any('/{namespace}/{resource}[/{params:.*}]', [ApiController::class, 'process']);
    });
};
