<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated()) {
        if (!UserRole::isSuperAdmin($this->getUser()->getRole()->value)) {
            return $this->response([
                'message' => 'Forbidden',
                'status' => false,
            ], 403);
        }
        
        $reportId = (int) $this->data['report_id'] ?? null;
        $reportData = [
            'frequency' => empty($this->data['frequency']) ? null : $this->data['frequency'],
            'dayOfWeek' => empty((int) $this->data['dayOfWeek']) ? null : (int) $this->data['dayOfWeek'],
            'dayOfMonth' => empty((int) $this->data['dayOfMonth']) ? null : (int) $this->data['dayOfMonth'],
            'month' => empty((int) $this->data['month']) ? null : (int) $this->data['month'],
            'time' => isset($this->data['time']) ? date('H:i', strtotime($this->data['time'])) : null,
            'recipients' => array_map('intval', $this->data['recipients']) ?? []
        ];

        $result = $this->reportService->updateReportSettingsById($reportId, $reportData);

        if (!$result) {
            return $this->response([
                'message' => 'Forbidden',
                'status' => false
            ], 403);
        }
        
        return $this->response([
            'message' => 'Report Updated',
            'status'  => true
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
