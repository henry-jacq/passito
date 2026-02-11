<?php

use App\Enum\UserRole;
use App\Entity\Logbook;
use App\Enum\OutpassStatus;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && ($this->paramsExists(['qr_payload']) || $this->paramsExists(['outpass_id'])) && UserRole::isVerifier($this->getRole())) {
        $user = $this->getUser();
        $verifier = $this->verifierService->getVerifierByUser($user);

        if (!$verifier || !$this->verifierService->isActiveVerifier($verifier)) {
            return $this->response([
                'message' => 'Verifier is inactive.',
                'status' => false
            ], 403);
        }

        $verifierMode = $this->outpassService->getVerifierMode();
        if ($verifierMode === \App\Enum\VerifierMode::AUTOMATED) {
            return $this->response([
                'message' => 'Verifier access is disabled.',
                'status' => false
            ], 403);
        }

        $outpassId = (int) ($this->data['outpass_id'] ?? 0);
        if ($outpassId <= 0) {
            $secret = $this->config->get('app.qr_secret');
            $decrypted = $this->outpassService->decryptQrData((string) $this->data['qr_payload'], $secret);
            $decoded = $decrypted ? json_decode($decrypted, true) : null;
            $outpassId = (int) ($decoded['id'] ?? 0);
        }
        if ($outpassId <= 0) {
            return $this->response([
                'message' => 'Invalid QR code.',
                'status' => false
            ], 400);
        }

        $outpass = $this->outpassService->getOutpassById($outpassId);
        if (!$outpass) {
            return $this->response([
                'message' => 'Outpass not found.',
                'status' => false
            ], 404);
        }
        if ($outpass->getStatus() !== OutpassStatus::APPROVED) {
            return $this->response([
                'message' => "Outpass is " . $outpass->getStatus()->label(),
                'status' => false
            ], 403);
        }

        $log = $this->em->getRepository(Logbook::class)->findOneBy(['outpass' => $outpass]);
        $hasCheckout = $log && $log->getOutTime() !== null;
        $hasCheckin = $log && $log->getInTime() !== null;

        return $this->response([
            'status' => true,
            'data' => [
                'id' => $outpass->getId(),
                'student_name' => $outpass->getStudent()->getUser()->getName(),
                'type' => $outpass->getTemplate()->getName(),
                'destination' => $outpass->getDestination(),
                'status' => $outpass->getStatus()->label(),
                'status_color' => $outpass->getStatus()->color(),
                'depart_date' => $outpass->getFromDate()?->format('d M, Y'),
                'depart_time' => $outpass->getFromTime()?->format('h:i A'),
                'return_date' => $outpass->getToDate()?->format('d M, Y'),
                'return_time' => $outpass->getToTime()?->format('h:i A'),
                'has_checkout' => $hasCheckout,
                'has_checkin' => $hasCheckin,
                'checkout_time' => $log?->getOutTime()?->format('d M, Y h:i A'),
                'checkin_time' => $log?->getInTime()?->format('d M, Y h:i A'),
            ]
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
