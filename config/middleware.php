<?php

use Slim\App;
use App\Core\View;
use App\Core\Config;
use Slim\Psr7\Request;
use App\Middleware\SessionStartMiddleware;

return function (App $app) {

    $container = $app->getContainer();
    $config = $container->get(Config::class);

    $customErrorHandler = function (
        Request $request,
        Throwable $exception
    ) use ($app) {

        global $container;
        
        $code = $exception->getCode();
        $view = $container->get(View::class);
        $response = $app->getResponseFactory()->createResponse();

        if ($view instanceof View) {

            $data = [
                'title' => "{$code} Error",
                'code' => $code
            ];
            
            $response->getBody()->write(
                (string) $view->createPage('error', $data, false)
                ->render()
            );

            return $response->withStatus($code);
        }
        $payload = ['error' => $exception->getMessage()];
        $response->getBody()->write(
            packJson($payload)
        );

        return $response
        ->withStatus($code)
        ->withHeader('Content-Type', 'application/json');
    };

    // $app->add(SessionStartMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(
        (bool) $config->get('app.display_error_details'),
        (bool) $config->get('app.log_errors'),
        (bool) $config->get('app.log_error_details')
    );
    
    if (!$config->get('app.display_error_details')) {
        $errorMiddleware->setDefaultErrorHandler($customErrorHandler);
    }
};
