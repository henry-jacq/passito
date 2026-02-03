<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['program_name', 'course_name', 'short_code', 'duration', 'institution_id']) && UserRole::isSuperAdmin($this->getRole())) {
        $institution = $this->academicService->getInstitutionById((int)$this->data['institution_id']);
        if (!$institution) {
            return $this->response([
                'message' => 'Institution not found',
                'status' => false
            ], 404);
        }

        $program = $this->academicService->createProgram([
            'program_name' => $this->data['program_name'],
            'course_name' => $this->data['course_name'],
            'short_code' => $this->data['short_code'],
            'duration' => $this->data['duration'],
            'institution' => $institution
        ]);

        if ($program) {
            return $this->response([
                'message' => 'Program created successfully',
                'status' => true
            ], 201);
        }

        return $this->response([
            'message' => 'Failed to create program',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
