<?php

use App\Enum\Gender;
use App\Entity\Hostel;
use App\Enum\UserRole;
use App\Enum\HostelType;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['hostel_name', 'category'])) {

        if (UserRole::isSuperAdmin($this->getRole())) {

            $superAdmin = $this->getAttribute('user');

            $hostel_type = $superAdmin->getGender()->value === Gender::MALE->value ? HostelType::GENTS : HostelType::LADIES;
            
            $data = [
                'hostel_name' => $this->data['hostel_name'],
                'category' => $this->data['category'],
                'hostel_type' => $hostel_type,
            ];

            $hostel = $this->academicService->createHostel($data);

            if ($hostel instanceof Hostel) {
                return $this->response([
                    'message' => 'Hostel created',
                    'status' => true,
                ], 201);
            }

            return $this->response([
                'message' => 'Hostel already exists',
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
