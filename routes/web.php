<?php

use Slim\App;
use App\Controller\HomeController;

return function (App $app) {
    $app->any('/', [HomeController::class, 'index']);
};
