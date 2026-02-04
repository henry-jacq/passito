<?php

${basename(__FILE__, '.php')} = function () {
    $token = $this->jwt->extractToken($this->slimRequest);
    if (empty($token)) {
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }

    $payload = $this->jwt->decode($token);
    if (!$payload || empty($payload['sub'])) {
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }

    $user = $this->userService->getUserById((int) $payload['sub']);
    if (!$user) {
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }

    $newToken = $this->jwt->createToken($user);

    return $this->response([
        'message' => 'Refreshed',
        'token' => $newToken,
        'token_type' => 'Bearer',
    ], 200, 'application/json', [
        'Set-Cookie' => $this->jwt->buildAuthCookieHeader($newToken),
    ]);
};
