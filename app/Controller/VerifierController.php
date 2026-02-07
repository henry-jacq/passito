<?php

namespace App\Controller;

use App\Core\View;
use App\Enum\UserRole;
use App\Services\VerifierService;
use App\Services\OutpassService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class VerifierController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        private readonly VerifierService $verifierService,
        private readonly OutpassService $outpassService
    )
    {
    }

    public function dashboard(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $userData = $request->getAttribute('user');
        if (!$userData || !UserRole::isVerifier($userData->getRole()->value)) {
            return $response->withHeader('Location', $this->view->urlFor('auth.login'))->withStatus(302);
        }

        $verifier = $this->verifierService->getVerifierByUser($userData);
        $logs = $verifier ? $this->verifierService->getLogs($verifier->getId()) : [];

        $args = [
            'title' => 'Verifier Console',
            'user' => $userData,
            'verifier' => $verifier,
            'logs' => array_slice($logs, 0, 10),
            'routeName' => $this->getRouteName($request),
            'breadcrumbs' => [
                [
                    'label' => 'Verifier',
                    'url' => null,
                ],
                [
                    'label' => 'Dashboard',
                    'url' => null,
                ],
            ],
        ];

        $args = array_merge($args, $this->view->getGlobals());
        return parent::render($request, $response, 'verifier/dashboard', $args);
    }
}
