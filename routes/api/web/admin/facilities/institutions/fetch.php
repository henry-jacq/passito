<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isSuperAdmin($this->getRole())) {
        $institutions = null;

        foreach ($this->facilityService->getInstitutions() as $institution) {
            $institutions[] = $institution->toArray();
        }

        // Check if there are no institutions
        if (empty($institutions)) {
            return $this->response([
                'message' => 'No institutions found',
                'status' => false,
            ], 404);
        }
        
        return $this->response([
            'message' => 'Institutions fetched',
            'status' => true,
            'data' => [
                'institutions' => $institutions
            ]
        ]);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
