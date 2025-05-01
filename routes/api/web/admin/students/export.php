<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {

    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $gender = $this->getAttribute('user');
        $students = $this->userService->getStudentsByGender($gender);

        if (empty($students)) {
            return $this->response([
                'message' => 'No students found',
                'status' => false
            ], 404);
        }

        // Generate a file name for the export CSV
        $csvPath = $this->storage->generateFileName('exports', 'csv');
        $fullPath = $this->storage->getFullPath($csvPath, true);

        $headers = ['name', 'email', 'digital_id', 'course', 'branch', 'year', 'room_no', 'hostel_id', 'student_contact', 'parent_contact'];

        $rows = $this->csvProcessor->mapDataToRows($students, function ($student) {
            return [
                $student->getUser()->getName(),
                $student->getUser()->getEmail(),
                $student->getDigitalId(),
                $student->getCourse(),
                $student->getBranch(),
                $student->getYear(),
                $student->getRoomNo(),
                $student->getHostel()->getId(),
                $student->getUser()->getContactNo(),
                $student->getParentNo()
            ];
        });

        if (!$this->csvProcessor->writeToFile($fullPath, $headers, $rows)) {
            return $this->response([
                'message' => 'Unable to create export file',
                'status' => false
            ], 500);
        }

        $fileName = 'students_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        return $this->response([
            'csvFile' => $fullPath
        ], 200, 'text/csv', $headers);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
