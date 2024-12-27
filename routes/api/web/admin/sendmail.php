<?php

// Send mail
${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['recipient', 'subject', 'body'])) {
        $recipient = $this->data['recipient'];
        $subject = $this->data['subject'];
        $body = $this->data['body'];
    
        try {
            $this->mail->addRecipient($recipient);
            $this->mail->addContent($subject, $body);
            
            foreach ($this->files['attachments'] as $file) {
                if ($file->getError() == UPLOAD_ERR_OK) {
                    $filePath = $file->getFilePath();
                    $fileName = $file->getClientFilename();
                    $this->mail->attachFile($filePath, $fileName);
                }
            }

            $this->mail->send();
            
        } catch (Exception $e) {
            return $this->response([
                'message' => $e->getMessage()
            ], 401);
        }
    
        return $this->response([
            'status' => true,
            'message' => 'Mail sent'
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
