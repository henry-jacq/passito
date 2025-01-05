<?php

use App\Entity\Verifier;
use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['verifier_id'])) {

        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $verifier = $this->verifierService->deleteVerifier($this->data['verifier_id']);

        if ($verifier) {
            return $this->response([
                'message' => 'Verifier Deleted',
                'status' => true
            ], 201);
        }

        return $this->response([
            'message' => 'Failed to delete verifier',
            'status' => false
        ], 500);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
