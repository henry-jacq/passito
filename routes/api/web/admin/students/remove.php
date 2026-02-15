<?php

use App\Enum\UserRole;
use App\Core\JobPayloadBuilder;
use App\Jobs\SendAccountDeletionEmail;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['student_id']) && UserRole::isAdministrator($this->getRole())) {
        $studentId = (int) $this->data['student_id'];

        $student = $this->userService->getStudentById($studentId);
        if (!$student) {
            return $this->response([
                'message' => 'Student not found',
                'status' => false
            ], 404);
        }

        $user = $student->getUser();
        $userId = $user?->getId();
        $userEmail = $user?->getEmail();
        $userName = $user?->getName();

        $removed = $this->userService->removeStudent($studentId);
        if ($removed) {
            // NOTE: user row is deleted by removeStudent(); include email/name so the job can still send.
            if (!empty($userEmail)) {
                $payload = JobPayloadBuilder::create()
                    ->set('user_id', (int) ($userId ?? 0))
                    ->set('to', $userEmail)
                    ->set('name', (string) ($userName ?? ''))
                    ->set('email', $userEmail);

                $this->queue->dispatch(SendAccountDeletionEmail::class, $payload);
            }

            return $this->response([
                'message' => 'Student removed',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Student not removed',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
