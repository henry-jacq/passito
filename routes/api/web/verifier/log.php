<?php

use App\Enum\UserRole;
use App\Enum\VerifierMode;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['outpass_id', 'action']) && UserRole::isVerifier($this->getRole())) {
        $user = $this->getUser();
        $verifier = $this->verifierService->getVerifierByUser($user);

        if (!$verifier || !$this->verifierService->isActiveVerifier($verifier)) {
            return $this->response([
                'message' => 'Verifier is inactive.',
                'status' => false
            ], 403);
        }

        $settings = $this->outpassService->getSettings($user->getGender());
        $verifierMode = $settings?->getVerifierMode();
        if ($verifierMode === VerifierMode::AUTOMATED) {
            return $this->response([
                'message' => 'Manual verification is disabled.',
                'status' => false
            ], 403);
        }

        $outpassId = (int) $this->data['outpass_id'];
        $action = $this->data['action'];

        if ($outpassId <= 0 || !in_array($action, ['checkout', 'checkin'], true)) {
            return $this->response([
                'message' => 'Invalid request.',
                'status' => false
            ], 400);
        }

        if ($action === 'checkout') {
            if ($this->verifierService->logExistsByOutpassId($outpassId)) {
                return $this->response([
                    'message' => 'Outpass already checked out.',
                    'status' => false
                ], 409);
            }

            $created = $this->verifierService->createLog($verifier, ['id' => $outpassId]);
            if ($created) {
                return $this->response([
                    'message' => 'Check-out recorded.',
                    'status' => true
                ]);
            }

            return $this->response([
                'message' => 'Outpass not found.',
                'status' => false
            ], 404);
        }

        $log = $this->em->getRepository(\App\Entity\Logbook::class)->findOneBy(['outpass' => $outpassId]);
        if (!$log) {
            return $this->response([
                'message' => 'Outpass has not been checked out yet.',
                'status' => false
            ], 409);
        }

        if ($log->getInTime() !== null) {
            return $this->response([
                'message' => 'Outpass already checked in.',
                'status' => false
            ], 409);
        }

        $this->verifierService->updateLog($log);

        return $this->response([
            'message' => 'Check-in recorded.',
            'status' => true
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
