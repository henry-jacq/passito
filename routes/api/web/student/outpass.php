<?php

use App\Entity\OutpassRequest;

// Send mail
${basename(__FILE__, '.php')} = function () {
    $expectedParams = [
        'from_date', 'to_date', 'from_time', 'to_time', 
        'outpass_type', 'destination'
    ];

    if ($this->isAuthenticated() && $this->paramsExists($expectedParams)) {
        // foreach ($this->files['attachments'] as $file) {
        //     if ($file->getError() == UPLOAD_ERR_OK) {
        //         // handle attachments
        //     }
        // }

        $user = $this->getAttribute('user');
        $student = $this->userService->getStudentByUser($user);
        
        $fromDate = new DateTime($this->data['from_date']);
        $toDate = new DateTime($this->data['to_date']);
        $fromTime = new DateTime($this->data['from_time']);
        $toTime = new DateTime($this->data['to_time']);

        $outpassData = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'outpass_type' => $this->data['outpass_type'],
            'destination' => $this->data['destination'],
            'student' => $student,
        ];

        $outpassData['purpose'] = empty($this->data['purpose']) ? '' : $this->data['purpose'];

        $outpass = $this->outpassService->createOutpass($outpassData);

        if ($outpass instanceof OutpassRequest) {
            return $this->response([
                'message' => 'Outpass Requested Successfully',
                'type' => 'success',
                'status' => true
            ], 201);
        } else {
            return $this->response([
                'message' => 'Failed to Request Outpass',
                'type' => 'error',
                'status' => false
            ], 409);
        }
    }

    return $this->response([
        'message' => 'Bad Request',
        'type' => 'error',
        'status' => false
    ], 400);
};
