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

        if (!$token) {
            return parent::renderErrorPage($response, ['code' => 400, 'message' => 'Missing verification token']);
        }

        $verification = $this->verificationService->getVerificationByToken($token);

        if (!$verification) {
            return parent::renderErrorPage($response, ['code' => 403, 'message' => 'Invalid or expired verification link']);
        }

        // Don’t allow reprocessing
        if ($verification->isUsed()) {
            $outpass = $verification->getOutpassRequest();
            return parent::render($request, $response, 'auth/parent', [
                'title' => 'Parental Verification',
                'outpass' => $outpass,
                'student' => $outpass->getStudent(),
                'verification' => $verification,
                'response' => $verification->getDecision()->value, // show final status
            ]);
        }

        // Process only if parent clicked Allow / Deny
        if ($parentResponse) {
            try {
                $verification = $this->verificationService->processDecision($verification, $parentResponse);
            } catch (\Throwable $e) {
                return parent::renderErrorPage($response, [
                    'code' => 400,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $outpass = $verification->getOutpassRequest();

        return parent::render($request, $response, 'auth/parent', [
            'title' => 'Parental Verification',
            'outpass' => $outpass,
            'student' => $outpass->getStudent(),
            'verification' => $verification,
            'response' => $parentResponse, // ensures template shows result
        ]);
    }
}
