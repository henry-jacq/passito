<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['program_id']) && UserRole::isSuperAdmin($this->getRole())) {
        $program = $this->academicService->getProgramById((int)$this->data['program_id']);
        if (!$program) {
            return $this->response([
                'message' => 'Program not found',
                'status' => false
            ], 404);
        }

        if ($this->academicService->programHasStudents($program)) {
            return $this->response([
                'message' => 'Cannot delete program while students are linked to it.',
                'status' => false
            ], 409);
        }

        $removed = $this->academicService->removeProgram((int)$this->data['program_id']);
        if ($removed) {
            return $this->response([
                'message' => 'Program removed',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to remove program',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
