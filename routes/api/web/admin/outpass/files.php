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
                
                $urls = [];
                foreach ($attachments as $attachment) {
                    $url = $this->view->urlFor('storage.admin', [
                        'id' => $admin->getId(),
                        'params' => $attachment
                    ]);
                    $urls[] = $url;
                }

                return $this->response([
                    'message' => 'Attachments retrieved successfully',
                    'status' => true,
                    'data' => $urls
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
