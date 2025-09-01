<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated()) {

        if (!UserRole::isAdministrator($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }
        
        $admin = $this->getAttribute('user');        
        $status = $this->adminService->approveAllPending($admin);

        if ($status) {
            return $this->response([
                'message' => 'Bulk Approval Finished',
                'status' => true
            ], 200);
        }

        return $this->response([
            'message' => 'Bulk Approval Failed',
            'status' => false
        ], 500);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
