<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $hostels = null;
        $admin = $this->getAttribute('user');

        foreach ($this->academicService->getHostelsByType($admin) as $hostel) {
            $hostel = $hostel->toArray();
            unset($hostel['warden']);
            $hostels[] = $hostel;
        }

        // Check if there are no hostels
        if (empty($hostels)) {
            return $this->response([
                'message' => 'Hostels have not been created!',
                'status' => false,
            ], 200);
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
