<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Enum\UserRole;
use App\Services\SystemSettingsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class SetupMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SystemSettingsService $settingsService
    )
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {   
        // Check if setup is already done
        $setupComplete = $this->settingsService->get('setup_complete', false);

        // Check if access setup route after setup is complete
        if ($setupComplete === true && strpos($request->getUri()->getPath(), '/setup') !== false) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        if ($setupComplete === false) {
            // Avoid redirect loop by checking if we are already on the install or update page
            $path = $request->getUri()->getPath();
            $installPath = $this->view->urlFor('setup.install');
            $updatePath = $this->view->urlFor('setup.update');

            if (strpos($path, $installPath) !== false || strpos($path, $updatePath) !== false) {
                return $handler->handle($request);  // Allow access to setup install/update page
            }

            return $this->responseFactory->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('setup.install'));
        }
        
        return $handler->handle($request);
    }
}
