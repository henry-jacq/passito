<?php

use App\Enum\UserRole;
use App\Entity\Verifier;

${basename(__FILE__, '.php')} = function () {
    $required = ['name', 'email', 'contact', 'location'];
    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isSuperAdmin($this->getRole())) {
        $admin = $this->getUser();
        $existing = $this->userService->getUserByEmail($this->data['email']);
        if ($existing) {
            return $this->response([
                'message' => 'A user with this email already exists.',
                'status' => false
            ], 409);
        }

        $userData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'role' => UserRole::VERIFIER,
            'gender' => $admin->getGender(),
            'contact' => $this->data['contact'],
        ];

        $user = $this->userService->createUser($userData);
        if (!$user) {
            return $this->response([
                'message' => 'Failed to create verifier user.',
                'status' => false
            ], 500);
        }

        $verifier = $this->verifierService->createManualVerifier($user, $this->data['location']);
        if ($verifier instanceof Verifier) {
            return $this->response([
                'message' => 'Manual verifier created successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to create manual verifier',
            'status' => false
        ], 500);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
