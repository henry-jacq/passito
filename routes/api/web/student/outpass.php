<?php

use App\Enum\UserRole;
use App\Entity\OutpassRequest;
use App\Core\JobPayloadBuilder;
use App\Jobs\SendParentApproval;
use App\Dto\CreateOutpassDto;

${basename(__FILE__, '.php')} = function () {

    $expectedParams = ['from_date', 'to_date', 'from_time', 'to_time', 'destination_text', 'type', 'reason_text'];
    
    if ($this->isAuthenticated() && $this->paramsExists($expectedParams)) {
        
        $user = $this->getAttribute('user');

        if (UserRole::isAdministrator($user->getRole()->value)) {
            return $this->response([
                'message' => 'Bad Request',
                'type' => 'error',
                'status' => false
            ], 400);
        }
        
        $student = $this->userService->getStudentByUser($user);
        $template = $this->outpassService->getTemplates($user, $this->data['type']);
        
        // If requests are disabled, return an error
        if ($this->adminService->isRequestLock($user->getGender()->value)) {
            return $this->response([
                'message' => 'Outpass requests are disabled.',
                'type' => 'error',
                'status' => false
            ], 403);
        }

        // Parse dates and times
        try {
            $fromDate = new DateTime($this->data['from_date']);
            $toDate = new DateTime($this->data['to_date']);
            $fromTime = new DateTime($this->data['from_time']);
            $toTime = new DateTime($this->data['to_time']);
        } catch (\Exception $e) {
            return $this->response([
                'message' => 'Invalid date or time format',
                'type' => 'error',
                'status' => false
            ], 400);
        }

        $destination = trim($this->data['destination_text']);
        $reason = empty($this->data['reason_text']) ? 'N/A' : trim($this->data['reason_text']);

        // Handle file attachments
        $attachments = [];
        if (isset($this->files['attachments']) && is_array($this->files['attachments'])) {
            foreach ($this->files['attachments'] as $file) {
                if ($file->getError() == UPLOAD_ERR_OK) {
                    $name = $file->getClientFilename();
                    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
                    $filePath = $file->getStream()->getMetadata('uri');

                    $name = $this->storage->generateFileName('attachments', $fileExtension);
                    $this->storage->moveUploadedFile($filePath, $name);

                    $attachments[] = $name;
                }
            }
        }

        // Extract custom parameters
        $customParams = array_values(array_diff(array_keys($this->data), $expectedParams));
        $customParams = array_intersect_key($this->data, array_flip($customParams));
        $csrfField = $this->view->csrfFieldName();
        if (array_key_exists($csrfField, $customParams)) {
            unset($customParams[$csrfField]);
        }

        // Create DTO with validation
        try {
            $outpassDto = CreateOutpassDto::create(
                student: $student,
                template: $template,
                fromDate: $fromDate,
                toDate: $toDate,
                fromTime: $fromTime,
                toTime: $toTime,
                destination: $destination,
                reason: $reason,
                attachments: $attachments,
                customValues: empty($customParams) ? null : $customParams
            );
        } catch (\InvalidArgumentException $e) {
            return $this->response([
                'message' => $e->getMessage(),
                'type' => 'error',
                'status' => false
            ], 400);
        }

        // Create outpass using DTO
        $outpass = $this->outpassService->createOutpass($outpassDto);
        
        $settings = $this->outpassService->getSettings($user->getGender());

        // Check if parent approval is required
        if ($settings->getParentApproval()) {
            // Dispatch job to send SMS to parent
            $parentPayload = JobPayloadBuilder::create();
            $parentPayload->set('outpass_id', $outpass->getId());
            $this->queue->dispatch(SendParentApproval::class, $parentPayload);
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
