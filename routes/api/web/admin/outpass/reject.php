<?php

use DateTime;
use App\Enum\UserRole;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $reason = $this->data['reason'] ?? null;
            $outpass = $this->outpassService->getOutpass($this->data['id']);

            if ($outpass instanceof OutpassRequest) {
                $outpass->setStatus(OutpassStatus::REJECTED);
                $outpass->setApprovedBy($this->getAttribute('user'));
                $outpass->setApprovedTime(new DateTime());
                if (empty($reason)) {
                    $outpass->setRemarks(null);
                } else {
                    $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
                    $outpass->setRemarks($reason);
                }

                // Update outpass details
                $this->outpassService->updateOutpass($outpass);

                return $this->response([
                    'message' => 'Outpass Rejected',
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
