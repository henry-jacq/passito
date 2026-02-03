<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['hostel_id'])) {
        if (UserRole::isSuperAdmin($this->getRole())) {
            $hostel = $this->academicService->getHostelById((int)$this->data['hostel_id']);

            if (!$hostel) {
                return $this->response([
                    'message' => 'Hostel not found',
                    'status' => false
                ], 404);
            }

            if ($this->academicService->hostelHasStudents($hostel)) {
                return $this->response([
                    'message' => 'Cannot delete hostel while students are assigned to it.',
                    'status' => false
                ], 409);
            }

            $removed = $this->academicService->removeHostel($this->data['hostel_id']);

            if ($removed) {
                return $this->response([
                    'message' => 'Hostel removed',
                    'status' => true
                ]);
            }

            return $this->response([
                'message' => 'Hostel not removed',
                'status' => false
            ], 400);
        }

        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
