<?php

use Slim\App;
use App\Controller\AuthController;
use App\Controller\HomeController;

return function (App $app) {
    $app->any('/', [HomeController::class, 'index']);
    $app->any('/login', [AuthController::class, 'login']);
};
