<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['assignment_id'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $removed = $this->adminService->removeAssignment((int)$this->data['assignment_id']);

        if ($removed) {
            return $this->response([
                'message' => 'Assignment removed',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to remove assignment',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
