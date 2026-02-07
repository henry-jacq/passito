<?php

use App\Enum\UserRole;
use App\Entity\Verifier;
use App\Enum\VerifierMode;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['verifier_id']) && UserRole::isSuperAdmin($this->getRole())) {
        $verifier = $this->verifierService->getVerifier((int) $this->data['verifier_id']);
        if (!$verifier instanceof Verifier || $verifier->getType() !== VerifierMode::MANUAL) {
            return $this->response([
                'message' => 'Manual verifier not found',
                'status' => false
            ], 404);
        }

        $updated = $this->verifierService->deactivate($this->data['verifier_id']);
        if ($updated instanceof Verifier) {
            return $this->response([
                'message' => 'Manual verifier deactivated',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to deactivate manual verifier',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
