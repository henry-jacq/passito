<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['template_id'])) {
        if (!UserRole::isSuperAdmin($this->getRole())) {
            return $this->response([
                'message' => 'Unauthorized'
            ], 401);
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

        if ($template->getIsSystemTemplate()) {
            return $this->response([
                'message' => 'System templates cannot be deleted.',
                'status' => false
            ], 409);
        }

        if ($this->outpassService->templateHasRequests($template)) {
            return $this->response([
                'message' => 'Cannot delete template while outpass requests exist.',
                'status' => false
            ], 409);
        }

        $this->outpassService->removeTemplate($template);

        return $this->response([
            'message' => 'Template removed',
            'status' => true
        ]);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
