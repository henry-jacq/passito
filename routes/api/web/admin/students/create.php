<?php

use App\Enum\UserRole;
use App\Entity\Student;
use App\Core\JobPayloadBuilder;
use App\Jobs\SendAccountCreationEmail;

${basename(__FILE__, '.php')} = function () {
    $required = ['name', 'email', 'digital_id', 'year', 'room_no', 'hostel_no', 'contact', 'parent_no', 'program', 'academic_year'];
    if ($this->isAuthenticated() && $this->paramsExists($required) && UserRole::isAdministrator($this->getRole())) {

        $gender = $this->getAttribute('user')->getGender();
        $email = strtolower(trim((string) ($this->data['email'] ?? '')));
        if ($email === '' || !filterEmail($email)) {
            return $this->response([
                'message' => 'Invalid email address',
                'status' => false
            ], 400);
        }

        // Prevent creating a student bound to an existing user record.
        $existingUser = $this->userService->getUserByEmail($email);
        if ($existingUser) {
            return $this->response([
                'message' => 'User with this email already exists',
                'status' => false
            ], 400);
        }

        $hostel = $this->academicService->getHostelById((int) $this->data['hostel_no']);
        $program = $this->academicService->getProgramById((int) $this->data['program']);
        $academicYear = $this->academicService->getAcademicYearById((int) $this->data['academic_year']);

        if ($academicYear === null) {
            return $this->response([
                'message' => 'Invalid academic year',
                'status' => false
            ], 400);
        }

        // Student
        $studentData = [
            'name' => $this->data['name'],
            'email' => $email,
            'role' => UserRole::USER,
            'gender' => $gender,
            'contact' => $this->data['contact'],
            'program' => $program,
            'hostel' => $hostel,
            'academic_year' => $academicYear,
            'digital_id' => (int) $this->data['digital_id'],
            'year' => (int) $this->data['year'],
            'room_no' => $this->data['room_no'],
            'parent_no' => (int) $this->data['parent_no'],
        ];

        $user = $this->userService->createUser($studentData);
        $student = $this->userService->createStudent($studentData, $user);

        if ($student instanceof Student) {
            $payload = JobPayloadBuilder::create()->set('user_id', $user->getId());

            $this->queue->dispatch(SendAccountCreationEmail::class, $payload);

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
