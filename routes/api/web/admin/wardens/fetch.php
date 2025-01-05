<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isSuperAdmin($this->getRole())) {
        $wardens = null;
        $superAdmin = $this->getAttribute('user');

        foreach ($this->userService->getWardensByGender($superAdmin) as $warden) {
            $wardens[] = $warden->toArray();
        }

        // Check if there are no wardens
        if (empty($wardens)) {
            return $this->response([
                'message' => 'No wardens found',
                'status' => false,
            ], 404);
        }

        return $this->response([
            'message' => 'Wardens fetched',
            'status' => true,
            'data' => [
                'wardens' => $wardens
            ]
        ], 200);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
