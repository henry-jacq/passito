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

        $verifier = $this->verifierService->activate($this->data['verifier_id']);

        if ($verifier instanceof Verifier) {
            return $this->response([
                'message' => 'Verifier Activated',
                'status' => true
            ], 201);
        }

        return $this->response([
            'message' => 'Failed to activate verifier',
            'status' => false
        ], 500);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
