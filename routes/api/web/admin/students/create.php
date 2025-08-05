<?php

use App\Enum\UserRole;
use App\Entity\Student;

${basename(__FILE__, '.php')} = function () {
    $required = ['name', 'email', 'digital_id', 'year', 'room_no', 'hostel_no', 'contact', 'parent_no', 'program'];
    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isAdministrator($this->getRole())) {

        $gender = $this->getAttribute('user')->getGender();

        // Student
        $studentData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'role' => UserRole::USER,
            'gender' => $gender,
            'contact' => $this->data['contact'],
            'program' => (int) $this->data['program'],
            'hostel_no' => $this->data['hostel_no'],
            'digital_id' => (int) $this->data['digital_id'],
            'year' => (int) $this->data['year'],
            'room_no' => $this->data['room_no'],
            'parent_no' => (int) $this->data['parent_no'],
        ];

        $user = $this->userService->createUser($studentData);
        $student = $this->userService->createStudent($studentData, $user);

        if ($student instanceof Student) {
            return $this->response([
                'message' => 'Student created successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to create student',
            'status' => false
        ], 500);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
