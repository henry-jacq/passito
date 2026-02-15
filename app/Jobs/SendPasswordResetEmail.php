<?php

namespace App\Jobs;

use App\Core\View;
use App\Services\MailService;
use App\Interfaces\JobInterface;
use App\Services\UserService;

class SendPasswordResetEmail implements JobInterface
{
    public function __construct(
        private readonly View $view,
        private readonly MailService $mailService,
        private readonly UserService $userService
    ) {}

    public function handle(array $payload): void
    {
        try {
            if (empty($payload['user_id']) || empty($payload['to']) || empty($payload['reset_link'])) {
                throw new \InvalidArgumentException(
                    'Invalid payload for SendPasswordResetEmail ' . json_encode($payload)
                );
            }

            $user = $this->userService->getUserById((int) $payload['user_id']);
            if (!$user) {
                throw new \RuntimeException('User not found for password reset email');
            }

            $expiresMinutes = (int) ($payload['expires_minutes'] ?? 60);
            if ($expiresMinutes <= 0) {
                $expiresMinutes = 60;
            }

            $body = $this->view->renderEmail('auth/password_reset', [
                'user' => $user,
                'reset_link' => (string) $payload['reset_link'],
                'expires_minutes' => $expiresMinutes,
            ]);

            $subject = (string) ($payload['subject'] ?? 'Reset your password');

            $this->mailService->notify(
                (string) $payload['to'],
                $subject,
                $body,
                true
            );
        } catch (\Throwable $e) {
            error_log("[SendPasswordResetEmail] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }
}

