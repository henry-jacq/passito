<?php

namespace App\Middleware;

use App\Core\View;
use App\Entity\User;
use App\Core\Session;
use App\Enum\UserRole;
use App\Entity\Settings;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class SetupMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Session $session,
        private readonly EntityManagerInterface $em,
        private readonly ResponseFactoryInterface $responseFactory
    )
    {
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {   
        // Check if setup is already done
        $settings = $this->em->getRepository(Settings::class)->findOneBy(['keyName' => 'setup_complete']);

        // Check if access setup route after setup is complete
        if ($settings && $settings->getValue() === 'true' && strpos($request->getUri()->getPath(), '/setup') !== false) {
            return $this->responseFactory
                ->createResponse(302)
                ->withHeader('Location', $this->view->urlFor('auth.login'));
        }

        if ($settings && $settings->getValue() === 'false') {
            // Avoid redirect loop by checking if we are already on the install page
            if (strpos($request->getUri()->getPath(), $this->view->urlFor('setup.install')) !== false) {
                return $handler->handle($request);  // Allow access to setup install page
            }
            return $this->responseFactory->createResponse(302)
            ->withHeader('Location', $this->view->urlFor('setup.install'));
        }
        
        return $handler->handle($request);
    }
}