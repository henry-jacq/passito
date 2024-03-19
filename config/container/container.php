<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(CONFIG_PATH . '/container/bindings.php');

return $containerBuilder->build();