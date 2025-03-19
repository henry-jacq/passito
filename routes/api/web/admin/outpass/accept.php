<?php

use App\Enum\UserRole;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $outpass = $this->outpassService->getOutpassById($this->data['id']);

            if ($outpass instanceof OutpassRequest) {
                $approvedBy = $this->getAttribute('user');
                $result = $this->adminService->approvePending($outpass, $approvedBy);

                // Update outpass details
                if ($result instanceof OutpassRequest) {
                    return $this->response([
                        'message' => 'Outpass Accepted',
                        'status' => true
                    ], 200);
                }
                
                return $this->response([
                    'message' => 'Failed to accept outpass',
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
