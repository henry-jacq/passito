<?php

use App\Enum\UserRole;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $outpass = $this->outpassService->getOutpassById($this->data['id']);

            if ($outpass instanceof OutpassRequest) {
                $reason = $this->data['reason'] ?? null;
                $approvedBy = $this->getAttribute('user');
                $result = $this->adminService->rejectPending($outpass, $approvedBy, $reason);

                if ($result instanceof OutpassRequest) {
                    return $this->response([
                        'message' => 'Outpass Rejected',
                        'status' => true
                    ], 200);
                }

                return $this->response([
                    'message' => 'Failed to reject outpass',
                    'status' => false
                ], 500);

            } else {
                return $this->response([
                    'message' => 'Outpass not found',
                    'status' => false
                ], 404);
            }
        } else {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
