<?php

use App\Enum\UserRole;
use App\Enum\VerifierMode;
use App\Enum\UserStatus;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && !$this->paramsExists(['email', 'password'])) {
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Already authenticated'
        ], 202);
    }
    
    if ($this->paramsExists(['email', 'password'])) {
        $authResult = $this->auth->login($this->data);
        
        if ($authResult) {
            $user = $authResult['user'];
            $token = $authResult['token'];
            $path = $this->view->urlFor('student.dashboard');

            if ($user->getStatus() !== UserStatus::ACTIVE) {
                usleep(mt_rand(400000, 1300000));
                return $this->response([
                    'message' => 'Account is inactive. Please contact support.',
                ], 403);
            }

            if (UserRole::isAdministrator($user->getRole()->value)) {
                $path = $this->view->urlFor('admin.dashboard');
                try {
                    $ttl = (int) $this->config->get('jwt.ttl', 3600);
                    $ipAddress = $this->slimRequest->getServerParams()['REMOTE_ADDR'] ?? null;
                    $userAgent = $this->slimRequest->getHeaderLine('User-Agent') ?: null;
                    $session = $this->loginSessionService->createAdminSession($user, $ipAddress, $userAgent, $ttl);
                    if ($session) {
                        $token = $this->jwt->createToken($user, $session->getTokenId());
                    }
                } catch (\Throwable $e) {
                    // Keep login functional even if session storage is not ready yet.
                }
            } elseif (UserRole::isVerifier($user->getRole()->value)) {
                $verifierMode = $this->outpassService->getVerifierMode();
                $verifier = $this->verifierService->getVerifierByUser($user);

                $isActiveVerifier = $verifier && $user->getStatus() === UserStatus::ACTIVE;
                $manualDisabled = $verifierMode === VerifierMode::AUTOMATED;

                if (!$isActiveVerifier || $manualDisabled) {
                    usleep(mt_rand(400000, 1300000));
                    return $this->response([
                        'message' => 'Verifier account is not active.',
                    ], 403);
                }

                $path = $this->view->urlFor('verifier.dashboard');
            }
            
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Authenticated',
                'redirect' => $this->resolveReturnUrl($path),
                'token' => $token,
                'token_type' => 'Bearer',
            ], 202, 'application/json', [
                'Set-Cookie' => $this->jwt->buildAuthCookieHeader($token),
            ]);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
