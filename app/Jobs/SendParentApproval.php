<?php

namespace App\Jobs;

use App\Services\SMSService;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;
use App\Services\ParentVerificationService;

class SendParentApproval implements JobInterface
{
    public function __construct(
        private readonly SMSService $sms,
        private readonly OutpassService $outpassService,
        private readonly ParentVerificationService $verificationService
    ) {}

    public function handle(array $payload): void
    {
        if (empty($payload['outpass_id'])) {
            throw new \InvalidArgumentException('Invalid payload for SendEmailJob ' . json_encode($payload));
        }

        $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);
        $student = $outpass->getStudent();
        $entry = $this->verificationService->createEntry($outpass);
        $message = $this->verificationService->getMessage($student->getUser(), $outpass, $entry);

        // Send SMS to Parent
        $this->sms->send($student->getParentNo(), $message);

    }
}
