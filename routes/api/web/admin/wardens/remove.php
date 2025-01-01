<?php

use App\Entity\User;
use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['warden_id'])) {

        // UserRole::isSuperAdmin($this->getRole()
        // user must be authenticated
        // super admin is the only one who can create wardens
        // Gender of super admin will be assigned to warden

        $user = $this->userService->removeWarden($this->data['warden_id']);

        if ($user) {
            return $this->response([
                'message' => 'Warden removed successfully',
                'status' => true
            ], 200);
        } else {
            return $this->response([
                'message' => 'Warden not found',
                'status' => false
            ], 404);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
