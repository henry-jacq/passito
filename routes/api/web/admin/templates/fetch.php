<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['template_id'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Forbidden',
                'status' => false
            ], 403);
        }

        $templateId = (int) $this->data['template_id'];
        if ($templateId <= 0) {
            return $this->response([
                'message' => 'Invalid template id',
                'status' => false
            ], 400);
        }

        $template = $this->outpassService->getTemplateById($templateId);
        if (!$template) {
            return $this->response([
                'message' => 'Template not found',
                'status' => false
            ], 404);
        }

        $fields = [];
        foreach ($template->getFields() as $field) {
            $fields[] = [
                'name' => $field->getFieldName(),
                'type' => $field->getFieldType(),
                'required' => $field->getIsRequired(),
                'system' => $field->getIsSystemField(),
            ];
        }

        return $this->response([
            'status' => true,
            'message' => 'Template fetched',
            'data' => [
                'id' => $template->getId(),
                'name' => $template->getName(),
                'description' => $template->getDescription(),
                'allowAttachments' => $template->getAllowAttachments(),
                'isActive' => $template->getIsActive(),
                'weekdayCollegeHoursStart' => $template->getWeekdayCollegeHoursStart()?->format('H:i'),
                'weekdayCollegeHoursEnd' => $template->getWeekdayCollegeHoursEnd()?->format('H:i'),
                'weekdayOvernightStart' => $template->getWeekdayOvernightStart()?->format('H:i'),
                'weekdayOvernightEnd' => $template->getWeekdayOvernightEnd()?->format('H:i'),
                'weekendStartTime' => $template->getWeekendStartTime()?->format('H:i'),
                'weekendEndTime' => $template->getWeekendEndTime()?->format('H:i'),
                'fields' => $fields,
            ]
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
