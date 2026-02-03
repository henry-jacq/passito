<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['warden_id', 'name', 'email', 'contact'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $warden = $this->userService->updateWarden((int)$this->data['warden_id'], [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'contact' => $this->data['contact']
        ]);

        if ($warden) {
            return $this->response([
                'message' => 'Warden updated successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to update warden',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
