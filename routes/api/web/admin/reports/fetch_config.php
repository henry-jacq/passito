<?php

use App\Enum\UserRole;
use DateTimeInterface;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['report_id'])) {
        if (!UserRole::isSuperAdmin($this->getUser()->getRole()->value)) {
            return $this->response([
                'message' => 'Forbidden',
                'status' => false,
            ], 403);
        }
        
        $reportId = (int) $this->data['report_id'];
        $report = $this->reportService->getReportSettingById($reportId);

        if (!$report) {
            return $this->response([
                'message' => 'Report not found',
                'status' => false,
            ], 404);
        }

        // filter out super admins from recipients for response
        $recipients = $report->getRecipients()
            ->filter(fn($user) => !UserRole::isSuperAdmin($user->getRole()->value))
            ->map(fn($user) => $user->toArray())
            ->toArray();

        // convert entity to array
        $data = $report->toArray();

        // override recipients
        $data['recipients'] = $recipients;

        // format time as HH:MM
        if (isset($data['time']) && $data['time'] instanceof DateTimeInterface) {
            $data['time'] = $data['time']->format('H:i'); // 24-hour format
        }

        // unset audit fields
        unset($data['createdAt'], $data['updatedAt']);

        return $this->response([
            'message' => 'Report fetched',
            'status'  => true,
            'data'    => $data,
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
