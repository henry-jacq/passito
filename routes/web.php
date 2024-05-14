<?php

use Slim\App;
use App\Controller\ApiController;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\AdminController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\AuthoriseMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->any('/login', [AuthController::class, 'login'])->add(AuthMiddleware::class);
    $app->any('/logout', [AuthController::class, 'logout']);
    
    $app->group('/', function(RouteCollectorProxy $group) {
        $group->any('', [HomeController::class, 'index']);
        $group->any('request', [HomeController::class, 'request']);
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
