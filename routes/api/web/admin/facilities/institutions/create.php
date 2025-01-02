<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['name', 'address', 'type'])) {

        // UserRole::isSuperAdmin($this->getRole()
        // user must be authenticated
        // super admin is the only one who can create wardens
        // Gender of super admin will be assigned to warden

        if (UserRole::isSuperAdmin($this->getRole())) {
            $data = [
                'name' => $this->data['name'],
                'address' => $this->data['address'],
                'type' => $this->data['type']
            ];

            $this->facilityService->createInstitution($data);

            return $this->response([
                'message' => 'Institution created',
                'status' => true,
            ], 201);
        }

        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
