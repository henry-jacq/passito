<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if (!$this->isAuthenticated() || !UserRole::isAdministrator($this->getRole())) {
        return $this->response([
            'message' => 'Bad Request',
            'status' => false,
        ], 400);
    }

    $academicYearId = (int) ($this->data['academic_year_id'] ?? 0);

    if ($academicYearId <= 0) {
        return $this->response([
            'message' => 'Academic year is required.',
            'status' => false,
        ], 400);
    }

    $academicYear = $this->academicService->getAcademicYearById($academicYearId);

    if (!$academicYear) {
        return $this->response([
            'message' => 'Invalid academic batch selected.',
            'status' => false,
        ], 400);
    }

    $promoteCurrentYear = filter_var(
        $this->data['promote_current_year'] ?? true,
        FILTER_VALIDATE_BOOLEAN,
        FILTER_NULL_ON_FAILURE
    );
    if ($promoteCurrentYear === null) {
        $promoteCurrentYear = true;
    }

    $deactivateExceeded = filter_var(
        $this->data['deactivate_exceeded'] ?? true,
        FILTER_VALIDATE_BOOLEAN,
        FILTER_NULL_ON_FAILURE
    );
    if ($deactivateExceeded === null) {
        $deactivateExceeded = true;
    }

    if (!$promoteCurrentYear && !$deactivateExceeded) {
        return $this->response([
            'message' => 'No changes requested. Enable promotion or exceeded-year deactivation.',
            'status' => false,
        ], 400);
    }

    $result = $this->userService->shiftStudentsCurrentYearByAcademicBatch(
        actor: $this->getUser(),
        academicYear: $academicYear,
        promoteCurrentYear: $promoteCurrentYear,
        deactivateExceeded: $deactivateExceeded
    );

    return $this->response([
        'message' => 'Student current year update completed.',
        'status' => true,
        'data' => $result,
    ]);
};
