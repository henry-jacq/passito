<?php

use App\Entity\User;
use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['name', 'email', 'contact'])) {

        // UserRole::isSuperAdmin($this->getRole()
        // user must be authenticated
        // super admin is the only one who can create wardens
        // Gender of super admin will be assigned to warden
        $superAdmin = $this->getAttribute('user');

        $warden = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'contact' => $this->data['contact'],
            'role' => UserRole::ADMIN, // warden
            'gender' => $superAdmin->getGender() // Gender of super admin
        ];


        $user = $this->userService->createWarden($warden);

        if ($user instanceof User) {
            return $this->response([
                'message' => 'Warden created successfully',
                'status' => true
            ], 201);
        } else {
            return $this->response([
                'message' => 'Warden with email already exists',
                'status' => false
            ], 409);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
