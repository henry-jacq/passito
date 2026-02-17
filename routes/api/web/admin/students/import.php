<?php

use App\Enum\UserRole;

${basename(__FILE__, '.php')} = function () {

    if ($this->isAuthenticated() && UserRole::isAdministrator($this->getRole())) {
        $hostels = $this->academicService->getHostelsByType($this->getUser());
        if (empty($hostels)) {
            return $this->response([
                'status' => false,
                'message' => 'Hostels have not been created. Please create at least one hostel before importing students.',
            ], 400);
        }

        $file = $this->files['file'] ?? null;

        if ($file && $file->getError() === UPLOAD_ERR_OK) {

            $result = $this->adminService->bulkUpload($file, $this->getUser());

            if (!$result) {
                return $this->response([
                    'status' => false,
                    'message' => 'Students could not be created. Please check the file and try again.',
                    'errors' => $this->session->getFlash('error') ?: []
                ], 400);
            }

            return $this->response([
                'status' => $result,
                'message' => 'Students Created successfully.',
            ]);
        } else {
            return $this->response([
                'status' => false,
                'message' => 'File not uploaded or invalid.',
            ], 400);
        }
    }

    return $this->response([
        'message' => 'Bad Request',
        'status' => false
    ], 400);
};
