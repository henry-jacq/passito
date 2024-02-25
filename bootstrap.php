<?php

use Dotenv\Dotenv;

require 'vendor/autoload.php';
require 'config/constants.php';
require 'config/helper.php';

ini_set('error_reporting', -1);
ini_set('display_errors', true);
ini_set('log_errors_max_len', 0);
ini_set('assert.exception', 1);
ini_set('memory_limit', -1);

$dotenv = Dotenv::createImmutable(APP_PATH);
$dotenv->load();
