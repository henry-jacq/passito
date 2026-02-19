<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated()) {
        if (!UserRole::isAdministrator($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false,
            ], 401);
        }

        $admin = $this->getUser();
        if (!$admin) {
            return $this->response([
                'message' => 'Bad Request',
                'status' => false,
            ], 400);
        }

        $result = $this->outpassService->autoCloseExpiredApprovedOutpasses($admin);

        return $this->response([
            'message' => sprintf(
                'Auto-close finished. %d stale approved pass(es) marked as expired.',
                (int) ($result['updated'] ?? 0)
            ),
            'status' => true,
            'data' => $result,
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false,
    ], 400);
};
