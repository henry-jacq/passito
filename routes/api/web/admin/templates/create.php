<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && UserRole::isSuperAdmin($this->getRole())) {
        $name = $this->data['name'];
        $description = $this->data['description'];
        $visibility = $this->data['visibility'];
        $allowAttachments = filter_var($this->data['allow_attachments'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $fields = $this->data['fields'] ?? [];
        $warden = $this->getAttribute('user');

        if (!$name) return $this->response(['status' => false, 'message' => 'Template name is required.']);

        // Default fields to be always included
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
            // 'visibility' => $visibility,
            'allowAttachments' => $allowAttachments,
        ];

        try {
            $this->outpassService->createTemplate($warden->getGender(), $templateData, $finalFields);

            return $this->response(['status' => true, 'message' => 'Template created successfully.']);
        } catch (\Throwable $th) {
            return $this->response(['status' => false, 'message' => 'Failed to create template.', 'error' => $th->getMessage()]);
        }
    }

    return $this->response(['status' => false, 'message' => 'Unauthorized']);
};
