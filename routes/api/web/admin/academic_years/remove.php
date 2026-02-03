<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    $required = ['academic_year_id'];
    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isAdministrator($this->getRole())) {
        $removed = $this->academicService->removeAcademicYear((int)$this->data['academic_year_id']);

        if ($removed) {
            return $this->response([
                'message' => 'Academic year removed successfully',
                'status' => true,
            ]);
        }

        return $this->response([
            'message' => 'Failed to remove academic year',
            'status' => false,
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false,
    ], 400);
};
