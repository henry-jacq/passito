<?php

use Slim\App;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\AdminController;

return function (App $app) {
    $app->any('/', [HomeController::class, 'index']);
    $app->any('/login', [AuthController::class, 'login']);
    $app->any('/admin', [AdminController::class, 'home']);
};
