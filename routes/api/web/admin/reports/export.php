<?php

use App\Enum\UserRole;
use App\Entity\ReportConfig;
use App\Enum\ReportKey;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['key'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Forbidden',
                'status'  => false,
            ], 403);
        }

        $user = $this->getAttribute('user');
        $key  = $this->data['key']; // e.g. daily_movement, late_arrivals

        $reportKey = ReportKey::tryFrom($key);
        if (!$reportKey) {
            return $this->response([
                'message' => 'Invalid report key',
                'status'  => false,
            ], 400);
        }

        // Find the report configuration for this key
        $config = $this->reportService->getReportSettingByKey($reportKey, $user);

        if (!$config) {
            return $this->response([
                'message' => 'Report config not found',
                'status'  => false
            ], 404);
        }

        // Generate the report through ReportService
        $csvPath = $this->reportService->generateReport($user, $config);

        if (!$csvPath) {
            return $this->response([
                'message' => 'Failed to generate report',
                'status'  => false
            ], 500);
        }

        $fullPath = $this->storage->getFullPath($csvPath, true);
        $fileName = $key . '_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Return the CSV file as a download
        return $this->response([
            'csvFile' => $fullPath
        ], 200, 'text/csv', $headers);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status'  => false
    ], 400);
};
