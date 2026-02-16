<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if (!$this->isAuthenticated()) {
        return $this->response([
            'message' => 'Unauthorized',
            'status' => false,
        ], 401);
    }

    if (!$this->paramsExists(['token_id'])) {
        return $this->response([
            'message' => 'Bad Request',
            'status' => false,
        ], 400);
    }

    $user = $this->getUser();
    if (!$user || !UserRole::isAdministrator($user->getRole()->value)) {
        return $this->response([
            'message' => 'Forbidden',
            'status' => false,
        ], 403);
    }

    $token = $this->jwt->extractToken($this->slimRequest);
    $payload = $token ? $this->jwt->decode($token) : null;
    $currentSid = (string) ($payload['sid'] ?? '');

    $tokenId = (string) $this->data['token_id'];
    $revoked = $this->loginSessionService->revokeForUser($user, $tokenId);
    if (!$revoked) {
        return $this->response([
            'message' => 'Session not found',
            'status' => false,
        ], 404);
    }

    $headers = [];
    $forceLogout = false;
    if ($currentSid !== '' && hash_equals($currentSid, $tokenId)) {
        $headers['Set-Cookie'] = $this->jwt->buildLogoutCookieHeader();
        $forceLogout = true;
    }

    return $this->response([
        'message' => 'Session revoked successfully',
        'status' => true,
        'force_logout' => $forceLogout,
    ], 200, 'application/json', $headers);
};
