<?php

namespace App\Middleware;

use App\Core\View;
use App\Core\Request;
use App\Core\Session;
use App\Entity\Settings;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class MaintenanceMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly Request $requestService,
        private readonly EntityManagerInterface $em,
        private readonly ResponseFactoryInterface $responseFactory
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $setting = $this->em->getRepository(Settings::class)->findOneBy([
            'keyName' => 'maintenance_mode'
        ]);

        $isMaintenance = filter_var($setting?->getValue(), FILTER_VALIDATE_BOOLEAN);

        if ($isMaintenance) {
            //$this->session->destroy();

            if ($this->requestService->isXhr($request)) {
                // Respond with JSON for API calls
                $response = $this->responseFactory->createResponse(503);
                $response->getBody()->write(json_encode([
                    'error' => 'Service temporarily unavailable due to maintenance.',
                    'code' => 503
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }

            // Regular web request – show maintenance page
            $response = $this->responseFactory->createResponse(503);
            $response->getBody()->write(
                (string) $this->view->createPage('maintenance', [
                    'title' => "Under Maintenance"
                ])->render()
            );
            return $response;
        }

        return $handler->handle($request);
    }
}
