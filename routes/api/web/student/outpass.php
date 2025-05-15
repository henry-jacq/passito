<?php

use App\Entity\OutpassRequest;

${basename(__FILE__, '.php')} = function () {

    $expectedParams = ['from_date', 'to_date', 'from_time', 'to_time', 'destination_text', 'type', 'reason_text'];
    
    if ($this->isAuthenticated() && $this->paramsExists($expectedParams)) {
        $user = $this->getAttribute('user');
        $student = $this->userService->getStudentByUser($user);
        $template = $this->outpassService->getTemplates($user, $this->data['type']);
        
        $fromDate = new DateTime($this->data['from_date']);
        $toDate = new DateTime($this->data['to_date']);
        $fromTime = new DateTime($this->data['from_time']);
        $toTime = new DateTime($this->data['to_time']);
        $destination = $this->data['destination_text'];
        
        // Check if the dates and times are valid
        if ($fromDate > $toDate) {
            return $this->response([
                'message' => 'Invalid Date Range',
                'type' => 'error',
                'status' => false
            ], 400);
        }

        if ($fromTime > $toTime) {
            return $this->response([
                'message' => 'Invalid Time Range',
                'type' => 'error',
                'status' => false
            ], 400);
        }

        $names = [];

        if (isset($this->files['attachments']) && is_array($this->files['attachments'])) {
            foreach ($this->files['attachments'] as $file) {
                if ($file->getError() == UPLOAD_ERR_OK) {
                    $name = $file->getClientFilename();
                    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
                    $filePath = $file->getStream()->getMetadata('uri');

                    $name = $this->storage->generateFileName('attachments', $fileExtension);
                    $this->storage->moveUploadedFile($filePath, $name);

                    $names[] = $name;
                }
            }
        }

        // Filter out custom parameters
        $customParams = array_values(array_diff(array_keys($this->data), $expectedParams));
        $customParams = array_intersect_key($this->data, array_flip($customParams));

        $outpassData = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'from_time' => $fromTime,
            'to_time' => $toTime,
            'template' => $template,
            'destination' => $destination,
            'student' => $student,
            'attachments' => $names,
            'custom_values' => $customParams ?? null,
        ];

        $outpassData['reason'] = empty($this->data['reason_text']) ? 'N/A' : $this->data['reason_text'];

        $outpass = $this->outpassService->createOutpass($outpassData);
        
        $settings = $this->outpassService->getSettings($user->getGender());

        // Check if parent approval is required
        if ($settings->getParentApproval()) {
            $entry = $this->verificationService->createEntry($outpass);
            $message = $this->verificationService->getMessage($user, $outpass, $entry);
            $this->sms->send($student->getParentNo(), $message);
        }

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
