<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['hostel_id', 'hostel_name', 'category'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }

        $hostel = $this->academicService->getHostelById((int)$this->data['hostel_id']);
        if (!$hostel) {
            return $this->response([
                'message' => 'Hostel not found',
                'status' => false
            ], 404);
        }

        $updated = $this->academicService->updateHostel((int)$this->data['hostel_id'], [
            'hostel_name' => $this->data['hostel_name'],
            'category' => $this->data['category'],
            'hostel_type' => $hostel->getHostelType(),
        ]);

        if ($updated) {
            return $this->response([
                'message' => 'Hostel updated',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Hostel not updated',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
