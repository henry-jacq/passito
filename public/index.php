<?php

declare(strict_types=1);

use Slim\App;

include __DIR__ . '/../bootstrap.php';

$container = require CONFIG_PATH . '/container/container.php';

$container->get(App::class)->run();
