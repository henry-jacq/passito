<?php

use App\Enum\UserRole;
use App\Enum\OutpassStatus;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $outpass = $this->outpassService->getOutpassById($this->data['id']);

            if ($outpass instanceof OutpassRequest) {
                $outpass->setStatus(OutpassStatus::APPROVED);
                $outpass->setRemarks(null);
                $outpass->setApprovedTime($time = new DateTime());
                $outpass->setApprovedBy($this->getAttribute('user'));

                $accepted = $this->view->renderEmail('outpass/accepted', [
                    'studentName' => $outpass->getStudent()->getUser()->getName(),
                    'outpass' => $outpass,
                ]);
                
                $userEmail = $outpass->getStudent()->getUser()->getEmail();
                $subject = "Your Outpass Request #{$outpass->getId()} Has Been Approved";

                $qrData = [
                    'id' => $outpass->getId(),
                    'type' => $outpass->getPassType()->value,
                    'student' => $userEmail,
                ];

                // Generate QR code and outpass document
                $qrCodePath = $this->outpassService->generateQRCode($qrData);
                $outpass->setQrCode(basename($qrCodePath));
                
                $documentPath = $this->outpassService->generateOutpassDocument($outpass);
                $outpass->setDocument(basename($documentPath));
                
                $attachments = [$documentPath, $qrCodePath];
                
                // Update outpass status
                $result = $this->outpassService->updateOutpass($outpass);
                $queue = $this->mail->queueEmail(
                    $subject, $accepted, $userEmail, $attachments
                );

                // Update outpass details
                if ($result instanceof OutpassRequest && $queue) {
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
