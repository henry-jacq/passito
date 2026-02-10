<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isSuperAdmin($this->getRole())) {
        $templateId = (int) ($this->data['template_id'] ?? 0);
        $name = $this->data['name'] ?? null;
        $description = $this->data['description'] ?? null;
        $allowAttachments = filter_var($this->data['allow_attachments'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $isActive = filter_var($this->data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $fields = $this->data['fields'] ?? [];

        if ($templateId <= 0 || !$name || !$description) {
            return $this->response(['status' => false, 'message' => 'Template details are required.'], 400);
        }

        $defaultFields = [
            ['name' => 'From Date', 'type' => 'date', 'required' => true, 'system' => true],
            ['name' => 'From Time', 'type' => 'time', 'required' => true, 'system' => true],
            ['name' => 'To Date', 'type' => 'date', 'required' => true, 'system' => true],
            ['name' => 'To Time', 'type' => 'time', 'required' => true, 'system' => true],
            ['name' => 'Destination', 'type' => 'text', 'required' => true, 'system' => true],
            ['name' => 'Reason', 'type' => 'text', 'required' => true, 'system' => true]
        ];

        $defaultNames = array_column($defaultFields, 'name');
        $customFields = array_filter($fields, function ($f) use ($defaultNames) {
            return !in_array($f['name'], $defaultNames, true);
        });

        $finalFields = array_merge($defaultFields, $customFields);

        $templateData = [
            'name' => $name,
            'description' => $description,
            'allowAttachments' => $allowAttachments,
            'isActive' => $isActive,
            'weekdayCollegeHoursStart' => $this->data['weekday_college_hours_start'] ?? null,
            'weekdayCollegeHoursEnd' => $this->data['weekday_college_hours_end'] ?? null,
            'weekdayOvernightStart' => $this->data['weekday_overnight_start'] ?? null,
            'weekdayOvernightEnd' => $this->data['weekday_overnight_end'] ?? null,
            'weekendStartTime' => $this->data['weekend_start_time'] ?? null,
            'weekendEndTime' => $this->data['weekend_end_time'] ?? null,
        ];

        try {
            $updated = $this->outpassService->updateTemplate($templateId, $templateData, $finalFields);
            if (!$updated) {
                return $this->response(['status' => false, 'message' => 'Template not found.'], 404);
            }

            return $this->response(['status' => true, 'message' => 'Template updated successfully.']);
        } catch (\Throwable $th) {
            return $this->response(['status' => false, 'message' => 'Failed to update template.', 'error' => $th->getMessage()]);
        }
    }

    return $this->response(['status' => false, 'message' => 'Unauthorized']);
};
