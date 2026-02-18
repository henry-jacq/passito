<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {

    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $gender = $this->getAttribute('user');
        $scope = strtolower(trim((string) ($this->data['scope'] ?? 'all')));
        $selectedIds = isset($this->data['student_ids']) && is_array($this->data['student_ids'])
            ? array_values(array_unique(array_filter(array_map('intval', $this->data['student_ids']), static fn ($id) => $id > 0)))
            : [];
        $programId = isset($this->data['program_id']) ? (int) $this->data['program_id'] : null;
        $academicYearId = isset($this->data['academic_year_id']) ? (int) $this->data['academic_year_id'] : null;

        if ($scope === 'selected') {
            if (empty($selectedIds)) {
                return $this->response([
                    'message' => 'Select at least one student to export',
                    'status' => false,
                ], 422);
            }
            $students = $this->userService->getStudentsForExport($gender, $selectedIds, $academicYearId, $programId);
        } else {
            $students = $this->userService->getStudentsForExport($gender, [], $academicYearId, $programId);
        }

        if (empty($students)) {
            return $this->response([
                'message' => 'No students found',
                'status' => false
            ], 404);
        }

        // Generate a file name for the export CSV
        $csvPath = $this->storage->generateFileName('exports', 'csv');
        $fullPath = $this->storage->getFullPath($csvPath, true);

        $headers = ['name', 'email', 'roll_no', 'program', 'branch', 'year', 'academic_year', 'room_no', 'hostel_name', 'student_no', 'parent_no'];

        $rows = $this->csvProcessor->mapDataToRows($students, function ($student) {
            return [
                $student->getUser()->getName(),
                $student->getUser()->getEmail(),
                $student->getRollNo(),
                $student->getProgram()->getProgramName(),
                $student->getProgram()->getShortCode(),
                $student->getYear(),
                $student->getAcademicYear()?->getLabel() ?? '',
                $student->getRoomNo(),
                $student->getHostel()->getHostelName(),
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
