<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $academicYears = [];

        foreach ($this->academicService->getAcademicYears($this->getAttribute('user')) as $academicYear) {
            $academicYears[] = $academicYear->toArray();
        }

        if (empty($academicYears)) {
            return $this->response([
                'message' => 'Academic years have not been created!',
                'status' => false,
            ], 200);
        }

        return $this->response([
            'message' => 'Academic years fetched',
            'status' => true,
            'data' => [
                'academic_years' => $academicYears
            ]
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
