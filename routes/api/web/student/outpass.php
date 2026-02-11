<?php

use App\Enum\UserRole;
use App\Entity\OutpassRequest;
use App\Entity\OutpassTemplate;
use App\Core\JobPayloadBuilder;
use App\Jobs\SendParentApproval;
use App\Dto\CreateOutpassDto;
use App\Enum\ResourceType;
use App\Enum\ResourceVisibility;

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
        if (!$template instanceof OutpassTemplate) {
            return $this->response([
                'message' => 'Invalid outpass template selected.',
                'type' => 'error',
                'status' => false
            ], 400);
        }
        
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

        $formatTime = static fn(?DateTime $time): ?string => $time ? $time->format('H:i') : null;
        $timeToMinutes = static function (string $time): int {
            [$h, $m] = array_map('intval', explode(':', $time));
            return ($h * 60) + $m;
        };
        $isTimeInWindow = static function (?string $time, ?string $start, ?string $end) use ($timeToMinutes): bool {
            if ($time === null || $start === null || $end === null) {
                return true;
            }
            $t = $timeToMinutes($time);
            $s = $timeToMinutes($start);
            $e = $timeToMinutes($end);
            if ($s <= $e) {
                return $t >= $s && $t <= $e;
            }
            return $t >= $s || $t <= $e;
        };

        $fromTimeStr = $fromTime->format('H:i');
        $toTimeStr = $toTime->format('H:i');
        $isWeekend = static function (DateTime $date): bool {
            $day = (int) $date->format('N'); // 1=Mon .. 7=Sun
            return $day >= 6;
        };

        $weekdayCollegeStart = $formatTime($template->getWeekdayCollegeHoursStart());
        $weekdayCollegeEnd = $formatTime($template->getWeekdayCollegeHoursEnd());
        $weekdayOvernightStart = $formatTime($template->getWeekdayOvernightStart());
        $weekdayOvernightEnd = $formatTime($template->getWeekdayOvernightEnd());
        $weekendStart = $formatTime($template->getWeekendStartTime());
        $weekendEnd = $formatTime($template->getWeekendEndTime());

        $validateWindow = function (DateTime $date, string $timeStr) use (
            $isWeekend,
            $isTimeInWindow,
            $weekdayCollegeStart,
            $weekdayCollegeEnd,
            $weekdayOvernightStart,
            $weekdayOvernightEnd,
            $weekendStart,
            $weekendEnd
        ): bool {
            if ($isWeekend($date)) {
                return $isTimeInWindow($timeStr, $weekendStart, $weekendEnd);
            }
            return
                $isTimeInWindow($timeStr, $weekdayCollegeStart, $weekdayCollegeEnd) ||
                $isTimeInWindow($timeStr, $weekdayOvernightStart, $weekdayOvernightEnd);
        };

        if (!$validateWindow($fromDate, $fromTimeStr) || !$validateWindow($toDate, $toTimeStr)) {
            return $this->response([
                'message' => 'Requested time does not fit into the selected outpass time window.',
                'type' => 'error',
                'status' => false
            ], 400);
        }

        // Handle file attachments
        $attachments = [];
        $storedAttachments = [];
        if (isset($this->files['attachments']) && is_array($this->files['attachments'])) {
            foreach ($this->files['attachments'] as $file) {
                if ($file->getError() == UPLOAD_ERR_OK) {
                    $stored = $this->fileService->createFromUpload(
                        $file,
                        'attachments',
                        ResourceType::OUTPASS_ATTACHMENT,
                        $student->getUser(),
                        null,
                        ResourceVisibility::OWNER
                    );

                    $attachments[] = $stored->getUuid();
                    $storedAttachments[] = $stored;
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
            foreach ($storedAttachments as $stored) {
                $this->fileService->updateResourceId($stored, $outpass->getId());
            }

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
