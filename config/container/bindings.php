<?php

use Slim\App;
use DI\Container;
use App\Core\View;
use App\Core\Config;
use App\Core\Mailer;
use App\Core\Request;
use App\Core\Session;
use App\Core\Database;
use function DI\create;
use Slim\Factory\AppFactory;
use PHPMailer\PHPMailer\PHPMailer;
use App\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

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
    Database::class => function (ContainerInterface $container) {
        $config = $container->get(Config::class)->get('db');
        $pdo = new \PDO(
            "{$config['driver']}:host={$config['host']};port={$config['port']};dbname={$config['dbname']}",
            $config['user'],
            $config['pass'],
            [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
        return Database::getConnection($pdo);
    },
    View::class => function(ContainerInterface $container){
        return new View($container->get(Config::class));
    },
    SessionInterface::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    Request::class => function(ContainerInterface $container) {
        return new Request($container->get(SessionInterface::class));
    },
    Mailer::class => function (ContainerInterface $container) {
        $phpMailer = new PHPMailer(true);
        return new Mailer(
            $phpMailer,
            $container->get(Config::class)
        );
    }
];

