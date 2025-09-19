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
        try {
            // Validate Payload
            if (empty($payload['subject']) || empty($payload['to']) || empty($payload['outpass_id']) || empty($payload['email_template'])) {
                throw new \InvalidArgumentException(
                    'Invalid payload for SendOutpassEmail ' . json_encode($payload)
                );
            }

            // Fetch Outpass
            try {
                $outpass = $this->outpassService->getOutpassById($payload['outpass_id']);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed fetching outpass: " . $e->getMessage(), 0, $e);
            }

            // Render Email Body
            try {
                $args = ['outpass' => $outpass];
                $body = $this->view->renderEmail($payload['email_template'], $args);
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed rendering email body: " . $e->getMessage(), 0, $e);
            }

            // Build Attachments
            $attachments = [];
            try {
                foreach ($payload['dependencies'] ?? [] as $depResult) {
                    if (!empty($depResult['pdfPath'])) {
                        $attachments[] = $depResult['pdfPath'];
                    }
                    if (!empty($depResult['qrCodePath'])) {
                        $attachments[] = $depResult['qrCodePath'];
                    }
                }
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed processing attachments: " . $e->getMessage(), 0, $e);
            }

            // Send Email
            try {
                $this->mailService->notify(
                    $payload['to'],
                    $payload['subject'],
                    $body,
                    true,
                    empty($attachments) ? null : $attachments
                );
            } catch (\Throwable $e) {
                throw new \RuntimeException("Failed sending email: " . $e->getMessage(), 0, $e);
            }
        } catch (\Throwable $e) {
            error_log("[SendOutpassEmail] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e; // rethrow so job system can retry/fail
        }
    }
}
