<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    $required = ['academic_year_id', 'label', 'start_year', 'end_year'];
    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isAdministrator($this->getRole())) {
        $academicYear = $this->academicService->updateAcademicYear((int)$this->data['academic_year_id'], [
            'label' => $this->data['label'],
            'start_year' => $this->data['start_year'],
            'end_year' => $this->data['end_year'],
        ]);

        if ($academicYear) {
            return $this->response([
                'message' => 'Academic year updated successfully',
                'status' => true,
            ]);
        }

        return $this->response([
            'message' => 'Failed to update academic year',
            'status' => false,
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false,
    ], 400);
};
