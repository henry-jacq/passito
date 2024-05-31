<?php

// Send mail
${basename(__FILE__, '.php')} = function () {
    $expectedParams = [
        'from_date', 'to_date', 'from_time', 
        'to_time', 'pass_type', 'destination',
        'subject', 'purpose'
    ];

    if ($this->paramsExists($expectedParams)) {

        usleep(mt_rand(400000, 1300000));
        
        try {

            foreach ($this->files['attachments'] as $file) {
                if ($file->getError() == UPLOAD_ERR_OK) {
                    // handle attachments
                }
            }
            
            $this->data['student_id'] = $this->getUserId();
            $this->outpass->create($this->data);
            
        } catch (Exception $e) {
            return $this->response([
                'message' => $e->getMessage()
            ], 401);
        }

        return $this->response([
            'status' => true,
            'message' => 'Outpass Created'
        ], 200);
    }

    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
