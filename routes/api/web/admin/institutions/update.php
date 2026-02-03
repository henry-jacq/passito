<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['institution_id', 'name', 'address', 'type']) && UserRole::isSuperAdmin($this->getRole())) {
        $institution = $this->academicService->updateInstitution((int)$this->data['institution_id'], [
            'name' => $this->data['name'],
            'address' => $this->data['address'],
            'type' => $this->data['type']
        ]);

        if ($institution) {
            return $this->response([
                'message' => 'Institution updated successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to update institution',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
