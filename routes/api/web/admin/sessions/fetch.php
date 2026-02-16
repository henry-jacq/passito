<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if (!$this->isAuthenticated()) {
        return $this->response([
            'message' => 'Unauthorized',
            'status' => false,
        ], 401);
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

    $sessions = $this->loginSessionService->getUserSessions($user);
    $data = array_map(static function ($session) use ($currentSid) {
        return [
            'token_id' => $session->getTokenId(),
            'ip_address' => $session->getIpAddress(),
            'user_agent' => $session->getUserAgent(),
            'is_active' => $session->getIsActive(),
            'created_at' => $session->getCreatedAt()->format('Y-m-d H:i:s'),
            'expires_at' => $session->getExpiresAt()->format('Y-m-d H:i:s'),
            'revoked_at' => $session->getRevokedAt()?->format('Y-m-d H:i:s'),
            'is_current' => $session->getTokenId() === $currentSid,
        ];
    }, $sessions);

    return $this->response([
        'message' => 'Login sessions fetched successfully',
        'status' => true,
        'data' => $data,
    ], 200);
};
