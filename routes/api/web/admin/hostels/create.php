<?php

use App\Enum\Gender;
use App\Entity\Hostel;
use App\Enum\UserRole;
use App\Enum\HostelType;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['hostel_name', 'warden_id', 'category', 'institution_id'])) {

        if (UserRole::isSuperAdmin($this->getRole())) {

            $superAdmin = $this->getAttribute('user');

            $hostel_type = $superAdmin->getGender()->value === Gender::MALE->value ? HostelType::GENTS : HostelType::LADIES;
            
            $data = [
                'hostel_name' => $this->data['hostel_name'],
                'warden_id' => $this->data['warden_id'],
                'category' => $this->data['category'],
                'hostel_type' => $hostel_type,
            ];

            $hostel = $this->facilityService->createHostel($data);

            if ($hostel instanceof Hostel) {
                return $this->response([
                    'message' => 'Hostel created',
                    'status' => true,
                ], 201);
            }

            return $this->response([
                'message' => 'Hostel not created',
                'status' => false,
            ], 500);
        }

        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
