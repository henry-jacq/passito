<?php

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
                $outpass->setAttachments(null);
                if (empty($reason)) {
                    $outpass->setRemarks(null);
                } else {
                    $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
                    $outpass->setRemarks($reason);
                }

                $rejected = $this->view->renderEmail('outpass/rejected', [
                    'studentName' => $outpass->getStudent()->getUser()->getName(),
                    'outpass' => $outpass,
                ]);

                $userEmail = $outpass->getStudent()->getUser()->getEmail();
                $subject = "Your Outpass Request #{$outpass->getId()} Has Been Rejected";

                // Update outpass status
                $result = $this->outpassService->updateOutpass($outpass);
                $queue = $this->mail->queueEmail($subject, $rejected, $userEmail, null);

                if ($result instanceof OutpassRequest && $queue) {
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
