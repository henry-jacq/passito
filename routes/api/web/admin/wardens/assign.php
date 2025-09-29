<?php

use App\Enum\AssignmentTarget;
use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['warden_id', 'assignment_type', 'assignment_data'])) {

        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Forbidden',
                'status'  => false,
            ], 403);
        }

        $wardenId       = (int) $this->data['warden_id'];
        $assignmentType = AssignmentTarget::tryFrom($this->data['assignment_type']);
        $assignmentData = (array) $this->data['assignment_data'];

        if (!$wardenId || !$assignmentType || empty($assignmentData)) {
            return $this->response([
                'message' => 'Invalid request data',
                'status'  => false,
            ], 400);
        }

        $warden = $this->userService->getUserById($wardenId);
        if (!$warden) {
            return $this->response([
                'message' => 'Warden not found',
                'status'  => false,
            ], 404);
        }

        // Assign the warden for given assignment type
        $result = $this->adminService->assignWarden(
            $warden, $this->getUser(), $assignmentType, $assignmentData,
        );

        if (!$result) {
            return $this->response([
                'message' => 'Failed to assign warden',
                'status'  => false,
            ], 500);
        }

        return $this->response([
            'message' => 'Warden assignment updated successfully',
            'status'  => true,
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status'  => false,
    ], 400);
};
