<?php

namespace App\Jobs;

use App\Core\View;
use App\Services\MailService;
use App\Interfaces\JobInterface;
use App\Services\OutpassService;

class SendOutpassEmail implements JobInterface
{
    public function __construct(
        private readonly View $view,
        private readonly MailService $mailService,
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload): void
    {
        if (empty($payload['subject']) || empty($payload['to']) || empty($payload['outpass_id']) || empty($payload['email_template'])) {
            throw new \InvalidArgumentException('Invalid payload for SendEmailJob ' . json_encode($payload));
        }

        $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);
        $args = ['outpass' => $outpass];
        
        // Prepare email body with email template args from payload
        $body = $this->view->renderEmail($payload['email_template'], $args ?? []);

        $attachments = [];
        
        // Attach files from dependencies if any
        foreach ($payload['dependencies'] ?? [] as $depResult) {
            if (!empty($depResult['pdfPath'])) {
                $attachments[] = $depResult['pdfPath'];
            }
            if (!empty($depResult['qrCodePath'])) {
                $attachments[] = $depResult['qrCodePath'];
            }
        }

        // Send email using MailService
        $this->mailService->notify($payload['to'], $payload['subject'], $body, true, empty($attachments) ? null : $attachments);

    }
}
