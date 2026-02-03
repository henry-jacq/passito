<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['institution_id']) && UserRole::isSuperAdmin($this->getRole())) {
        $institution = $this->academicService->getInstitutionById((int)$this->data['institution_id']);
        if (!$institution) {
            return $this->response([
                'message' => 'Institution not found',
                'status' => false
            ], 404);
        }

        if ($this->academicService->institutionHasPrograms($institution)) {
            return $this->response([
                'message' => 'Cannot delete institution while programs are linked to it.',
                'status' => false
            ], 409);
        }

        $this->academicService->deleteInstitution((int)$this->data['institution_id']);

        return $this->response([
            'message' => 'Institution removed',
            'status' => true
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
