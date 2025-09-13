<?php

namespace App\Jobs;

use App\Interfaces\JobInterface;
use App\Services\MailService;

class SendEmailJob implements JobInterface
{
    public function __construct(
        private readonly MailService $mailService
    ) {}

    public function handle(array $payload): void
    {
        if (empty($payload['to']) || empty($payload['subject']) || empty($payload['body'])) {
            throw new \InvalidArgumentException('Invalid payload for SendEmailJob');
        }

        $this->mailService->notify(
            $payload['to'],
            $payload['subject'],
            $payload['body'],
            true,
            $payload['attachments'] ?? []
        );
    }
}
