<?php

use Slim\App;
use App\Core\View;
use App\Core\Config;
use App\Core\Request;
use App\Core\Session;
use App\Core\Storage;
use App\Services\JwtService;
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
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use Aws\S3\S3Client;

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
            $container->get(JwtService::class),
            $container->get(\App\Services\FileService::class),
            $container->get(\App\Services\SecureLinkService::class),
            $routeParser
        );
    },
    Session::class => function (ContainerInterface $container) {
        return new Session($container->get(Config::class));
    },
    Storage::class => function(Config $config) {
        $driver = strtolower((string) $config->get('storage.driver', 'local'));

        if ($driver === 's3') {
            if (!class_exists(S3Client::class) || !class_exists(AwsS3V3Adapter::class)) {
                throw new RuntimeException('S3 storage driver selected, but required Flysystem S3 packages are not installed.');
            }

            $s3 = (array) $config->get('storage.s3', []);
            $bucket = (string) ($s3['bucket'] ?? '');
            if ($bucket === '') {
                throw new RuntimeException('S3 storage driver selected, but storage.s3.bucket is empty.');
            }

            $clientConfig = [
                'region' => $s3['region'] ?? 'us-east-1',
                'version' => $s3['version'] ?? 'latest',
                'credentials' => [
                    'key' => $s3['key'] ?? '',
                    'secret' => $s3['secret'] ?? '',
                ],
            ];

            if (!empty($s3['endpoint'])) {
                $clientConfig['endpoint'] = $s3['endpoint'];
            }
            if (!empty($s3['use_path_style_endpoint'])) {
                $clientConfig['use_path_style_endpoint'] = true;
            }

            $client = new S3Client($clientConfig);
            $adapter = new AwsS3V3Adapter($client, $bucket, (string) ($s3['prefix'] ?? ''));
            $filesystem = new Filesystem($adapter);

            return new Storage($filesystem, false, null);
        }

        $root = (string) $config->get('storage.local.root', STORAGE_PATH);
        $localAdapter = new LocalFilesystemAdapter($root);
        $fileSystem = new Filesystem($localAdapter);
        return new Storage($fileSystem, true, $root);
    }
];
