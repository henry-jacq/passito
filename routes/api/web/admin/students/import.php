<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {

    // need authenticated, isadminstrator 
    // get the uploaded file
    // validate the file like is it has the required columns
    // check if the file is a CSV
    // check if the file is not empty
    // check if the file is not too large

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

        // Open the CSV file for writing
        $handle = fopen($fullPath, 'w');
        if (!$handle) {
            return $this->response([
                'message' => 'Unable to create export file',
                'status' => false
            ], 500);
        }

        // Write the CSV header row
        fputcsv($handle, ['name', 'email', 'digital_id', 'course', 'branch', 'year', 'room_no', 'hostel_id', 'student_contact', 'parent_contact']);

        // Write student data to the CSV
        foreach ($students as $student) {
            fputcsv($handle, [
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
            ]);
        }

        fclose($handle);

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
