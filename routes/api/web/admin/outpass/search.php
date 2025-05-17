<?php

use App\Enum\UserRole;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['query'])) {

        if (UserRole::isAdministrator($this->getRole())) {

            $admin = $this->getAttribute('user');
            $outpass = $this->outpassService->searchOutpassRecords(
                $this->data['query'], $admin
            );

            if ($outpass) {
                return $this->response([
                    'message' => 'Success',
                    'status' => true,
                    'data' => $outpass
                ]);
            } else {
                return $this->response([
                    'message' => 'No Outpass Found',
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
