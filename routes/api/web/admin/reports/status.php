<?php

use App\Enum\UserRole;
use App\Entity\ReportConfig;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['report_id'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Forbidden',
                'status' => false,
            ], 403);
        }
        
        $reportId = (int) $this->data['report_id'];
        $report = $this->reportService->toggleReportStatus($reportId);
        
        if (!$report instanceof ReportConfig) {
            return $this->response([
                'message' => 'Report not found',
                'status' => false,
            ], 404);
        }

        return $this->response([
            'message' => 'Report status fetched',
            'status'  => true,
            'enabled' => $report->getIsEnabled()
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
