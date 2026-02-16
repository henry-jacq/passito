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

    $tokenId = (string) $this->data['token_id'];

    // Prevent deleting the currently active session directly.
    $token = $this->jwt->extractToken($this->slimRequest);
    $payload = $token ? $this->jwt->decode($token) : null;
    $currentSid = (string) ($payload['sid'] ?? '');
    if ($currentSid !== '' && hash_equals($currentSid, $tokenId)) {
        return $this->response([
            'message' => 'Revoke current session instead of deleting it directly',
            'status' => false,
        ], 409);
    }

    $deleted = $this->loginSessionService->deleteForUser($user, $tokenId);
    if (!$deleted) {
        return $this->response([
            'message' => 'Session not found',
            'status' => false,
        ], 404);
    }

    return $this->response([
        'message' => 'Session entry deleted',
        'status' => true,
    ], 200);
};
