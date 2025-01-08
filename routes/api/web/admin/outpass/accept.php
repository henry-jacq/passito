<?php

use App\Enum\UserRole;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $outpass = $this->outpassService->getOutpass($this->data['id']);

            if ($outpass instanceof OutpassRequest) {
                $outpass->setStatus(OutpassStatus::APPROVED);
                $outpass->setApprovedBy($this->getAttribute('user'));

                $this->outpassService->updateOutpass($outpass);

                return $this->response([
                    'message' => 'Outpass Accepted',
                    'status' => true
                ], 200);
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
