<?php

namespace App\Controller;

use App\Core\View;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use App\Controller\BaseController;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\ParentVerificationService;

class ParentController extends BaseController
{
    public function __construct(
        protected readonly View $view,
        private readonly EntityManagerInterface $em,
        private readonly ParentVerificationService $verificationService
    ) {}

    public function verify(Request $request, Response $response): Response
    {
        $this->view->clearCacheIfDev();

        $args = $request->getQueryParams();
        $token = $args['token'] ?? null;
        $parentResponse = $args['response'] ?? null;

        $verification = $this->verificationService->getVerificationByToken($token);
        $outpass = $verification->getOutpassRequest();

        if(!$token) {
            return parent::renderErrorPage($response, ['code' => 400,]);
        }

        if (!$verification || $verification->isUsed()) {
            return parent::renderErrorPage($response, ['code' => 403,]);
        }
        
        if ($parentResponse) {
            $verification = $this->verificationService->processDecision($verification, $parentResponse);
        }
        
        $args += [
            'title' => 'Parental Verification',
            'outpass' => $outpass,
            'student' => $outpass->getStudent(),
            'verification' => $verification
        ];

        return parent::render($request, $response, 'auth/parent', $args);
    }
}
