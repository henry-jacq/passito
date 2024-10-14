<?php

use Slim\App;
use App\Core\View;
use App\Core\Config;
use App\Core\Mailer;
use App\Core\Request;
use App\Core\Session;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

use function DI\create;

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

        return new EntityManager(
            DriverManager::getConnection($config->get('doctrine.connection'), $ormConfig),
            $ormConfig
        );
    },
    ResponseFactoryInterface::class => fn (App $app) => $app->getResponseFactory(),
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
            $container->get(Session::class),
            $routeParser
        );
    },
    Session::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    Mailer::class => function (ContainerInterface $container) {
        $phpMailer = new PHPMailer(true);
        return new Mailer(
            $phpMailer,
            $container->get(Config::class)
        );
    }
];
