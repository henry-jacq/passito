<?php

use App\Enum\UserRole;
use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->paramsExists(['id'])) {

        if (UserRole::isAdministrator($this->getRole())) {
            $outpass = $this->outpassService->getOutpassById($this->data['id']);

            if ($outpass instanceof OutpassRequest && !empty($outpass->getAttachments())) {
                $admin = $this->getAttribute('user');
                $attachments = $outpass->getAttachments();
                
                $files = [];
                foreach ($attachments as $attachment) {
                    if ($this->fileService->isUuid($attachment)) {
                        $url = $this->view->secureResourceUrl('file', $attachment);
                        $name = $this->fileService->getOriginalNameByUuid($attachment) ?? 'Attachment';
                        $files[] = [
                            'url' => $url,
                            'name' => $name,
                        ];
                    } else {
                        $url = $this->view->urlFor('storage.admin', [
                            'id' => $admin->getId(),
                            'params' => $attachment
                        ]);
                        $files[] = [
                            'url' => $url,
                            'name' => basename($attachment),
                        ];
                    }
                }

                return $this->response([
                    'message' => 'Attachments retrieved successfully',
                    'status' => true,
                    'data' => $files
                ], 200);
            } else {
                return $this->response([
                    'message' => 'Attachments not found',
                    'status' => false
                ], 404);
            }
        } else {
            return $this->response([
                'message' => 'Unauthorized',
                'status' => false
            ], 401);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
