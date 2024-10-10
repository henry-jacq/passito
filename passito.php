<?php

declare(strict_types = 1);

use App\Core\Config;
use Doctrine\Migrations\DependencyFactory;
use Symfony\Component\Console\Application;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

$app       = require 'bootstrap.php';
$container = $app->getContainer();
$config    = $container->get(Config::class);

$entityManager     = $container->get(EntityManagerInterface::class);
$dependencyFactory = DependencyFactory::fromEntityManager(
    new PhpFile(CONFIG_PATH . '/migrations.php'),
    new ExistingEntityManager($entityManager)
);

$migrationCommands = require CONFIG_PATH . '/commands/migration_commands.php';
$customCommands    = require CONFIG_PATH . '/commands/commands.php';

$cliApp = new Application($config->get('app.name'), $config->get('app.version'));

ConsoleRunner::addCommands($cliApp, new SingleManagerProvider($entityManager));

$cliApp->addCommands($migrationCommands($dependencyFactory));
$cliApp->addCommands(array_map(fn($command) => $container->get($command), $customCommands));

$cliApp->run();