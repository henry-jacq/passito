<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['status'])) {
        if (!UserRole::isAdministrator($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        // Toggle request lock using service
        // boolean state (true = locked, false = unlocked)
        $stat = $this->data['status'] == 'lock' ? 'true' : 'false';
        $lockStatus = $this->adminService->setLockRequests($stat);

        return $this->response([
            'message' => $lockStatus ? 'Outpass requests are now locked' : 'Outpass requests are now unlocked',
            'status'  => true,
            'locked'  => $lockStatus
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
