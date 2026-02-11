<?php

use App\Enum\UserRole;
use App\Enum\OutpassStatus;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['outpass_id'])) {
        $user = $this->getAttribute('user');

        if (UserRole::isAdministrator($user->getRole()->value)) {
            return $this->response([
                'message' => 'Bad Request',
                'type' => 'error',
                'status' => false
            ], 400);
        }

        $student = $this->userService->getStudentByUser($user);
        $outpassId = (int) $this->data['outpass_id'];
        $outpass = $this->outpassService->getOutpassById($outpassId);

        if (!$outpass || $outpass->getStudent()->getId() !== $student->getId()) {
            return $this->response([
                'message' => 'Outpass not found.',
                'type' => 'error',
                'status' => false
            ], 404);
        }

        if ($outpass->getStatus() !== OutpassStatus::PENDING) {
            return $this->response([
                'message' => 'Only pending outpasses can be cancelled.',
                'type' => 'error',
                'status' => false
            ], 403);
        }

        $outpass->setStatus(OutpassStatus::CANCELLED);
        $this->outpassService->updateOutpass($outpass);

        return $this->response([
            'message' => 'Outpass cancelled.',
            'type' => 'success',
            'status' => true
        ]);
    }

    return $this->response([
        'message' => 'Bad Request',
        'type' => 'error',
        'status' => false
    ], 400);
};
