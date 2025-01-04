<?php

use App\Entity\Verifier;
use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['verifier_name', 'location'])) {

        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $verifier = $this->verifierService->createVerifier(
            $this->data['verifier_name'], $this->data['location']
        );

        if ($verifier instanceof Verifier) {
            return $this->response([
                'message' => 'Verifier created successfully',
                'status' => true
            ], 201);
        }

        return $this->response([
            'message' => 'Failed to create verifier',
            'status' => false
        ], 500);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
