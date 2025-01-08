<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';
require 'config/constants.php';
require 'config/helper.php';

ini_set('error_reporting', -1);
ini_set('display_errors', true);
ini_set('log_errors_max_len', 0);
ini_set('assert.exception', 1);
ini_set('memory_limit', -1);

date_default_timezone_set("Asia/Kolkata");

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$container      = require CONFIG_PATH . '/container/container.php';
$addMiddlewares = require CONFIG_PATH . '/middleware.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$addMiddlewares($app);

return $app;