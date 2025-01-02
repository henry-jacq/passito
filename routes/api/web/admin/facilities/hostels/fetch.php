<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isSuperAdmin($this->getRole())) {
        $hostels = null;

        foreach ($this->facilityService->getHostels() as $hostel) {
            $hostels[] = $hostel->toArray();
        }

        // Check if there are no hostels
        if (empty($hostels)) {
            return $this->response([
                'message' => 'No hostels found',
                'status' => false,
            ], 404);
        }

        return $this->response([
            'message' => 'Hostels fetched',
            'status' => true,
            'data' => [
                'hostels' => $hostels
            ]
        ]);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
