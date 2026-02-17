<?php

use App\Enum\UserRole;
use App\Entity\Student;

${basename(__FILE__, '.php')} = function () {
    $required = [
        'student_id', 'name', 'email', 'roll_no', 'year', 'room_no',
        'hostel_no', 'contact', 'parent_no', 'program', 'academic_year', 'status'
    ];

    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isAdministrator($this->getRole())) {
        $hostel = $this->academicService->getHostelById((int) $this->data['hostel_no']);
        $program = $this->academicService->getProgramById((int) $this->data['program']);
        $academicYear = $this->academicService->getAcademicYearById((int) $this->data['academic_year']);

        if ($hostel === null) {
            return $this->response([
                'message' => 'Invalid hostel',
                'status' => false
            ], 400);
        }

        if ($program === null) {
            return $this->response([
                'message' => 'Invalid program',
                'status' => false
            ], 400);
        }

        if ($academicYear === null) {
            return $this->response([
                'message' => 'Invalid academic year',
                'status' => false
            ], 400);
        }

        $studentData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'contact' => $this->data['contact'],
            'program' => $program,
            'hostel' => $hostel,
            'academic_year' => $academicYear,
            'roll_no' => (int) $this->data['roll_no'],
            'year' => (int) $this->data['year'],
            'room_no' => $this->data['room_no'],
            'parent_no' => (int) $this->data['parent_no'],
            'status' => (int) $this->data['status']
        ];

        $student = $this->userService->updateStudent((int) $this->data['student_id'], $studentData);

        if ($student instanceof Student) {
            return $this->response([
                'message' => 'Student updated successfully',
                'status' => true
            ]);
        }

        return $this->response([
            'message' => 'Failed to update student',
            'status' => false
        ], 400);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
