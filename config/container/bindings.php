<?php

use Slim\App;
use App\Core\View;
use App\Core\Config;
use App\Core\Request;
use App\Core\Session;
use App\Core\Storage;
use function DI\create;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use League\Flysystem\Filesystem;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use League\Flysystem\Local\LocalFilesystemAdapter;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $middleware = require CONFIG_PATH . DIRECTORY_SEPARATOR . '/middleware.php';
        $router     = require ROUTES_PATH . DIRECTORY_SEPARATOR . 'web.php';

        $app = AppFactory::create();

        $router($app);

        $middleware($app);

        return $app;
    },
    Config::class => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    EntityManagerInterface::class => function (Config $config) {
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $config->get('doctrine.entity_dir'),
            $config->get('doctrine.dev_mode')
        );

        $ormConfig->setAutoGenerateProxyClasses(true);

        return new EntityManager(
            DriverManager::getConnection($config->get('doctrine.connection'), $ormConfig),
            $ormConfig
        );
    },
    ResponseFactoryInterface::class => fn (App $app) => $app->getResponseFactory(),
    StreamFactoryInterface::class => fn () => new Psr17Factory(),
    Request::class => function (ContainerInterface $container) {
        return new Request(
            $container->get(Session::class)
        );
    },
    View::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $routeParser = $app->getRouteCollector()->getRouteParser();

        return new View(
            $container->get(Config::class),
            $container->get(Storage::class),
            $container->get(Session::class),
            $routeParser
        );
    },
    Session::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    Storage::class => function() {
        $localAdapter = new LocalFilesystemAdapter(STORAGE_PATH);
        $fileSystem = new Filesystem($localAdapter);
        return new Storage($fileSystem);
    }
];
