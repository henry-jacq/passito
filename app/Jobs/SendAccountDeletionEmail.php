<?php

namespace App\Jobs;

use App\Core\View;
use App\Core\Config;
use App\Services\MailService;
use App\Interfaces\JobInterface;
use App\Services\UserService;

class SendAccountDeletionEmail implements JobInterface
{
    public function __construct(
        private readonly View $view,
        private readonly Config $config,
        private readonly MailService $mailService,
        private readonly UserService $userService
    ) {}

    public function handle(array $payload): void
    {
        try {
            $supportEmail = (string) ($payload['support_email'] ?? '');
            if ($supportEmail === '') {
                $supportEmail = (string) ($this->config->get('notification.admin_email')
                    ?? $this->config->get('notification.mail.from')
                    ?? '');
            }
            if ($supportEmail === '') {
                throw new \InvalidArgumentException('Missing support email for SendAccountDeletionEmail');
            }

            $user = null;
            $userIdRaw = $payload['user_id'] ?? null;
            if (is_numeric($userIdRaw) && (int) $userIdRaw > 0) {
                $user = $this->userService->getUserById((int) $userIdRaw);
                if (!$user && empty($payload['to']) && empty($payload['email'])) {
                    // Non-retryable: user was removed before worker processed this job.
                    error_log("[SendAccountDeletionEmail] User not found (user_id={$userIdRaw}); skipping job.");
                    return;
                }
            }

            $to = (string) ($payload['to'] ?? ($user?->getEmail() ?? ''));
            $name = (string) ($payload['name'] ?? ($user?->getName() ?? ''));
            if ($to === '' || $name === '') {
                throw new \InvalidArgumentException(
                    'Invalid payload for SendAccountDeletionEmail ' . json_encode($payload)
                );
            }

            $body = $this->view->renderEmail('auth/account_deleted', [
                'name' => $name,
                'email' => (string) ($payload['email'] ?? $to),
                'support_email' => $supportEmail,
                'app_name' => (string) ($payload['app_name'] ?? $this->config->get('app.name')),
            ]);

            $subject = (string) ($payload['subject'] ?? 'Your account has been removed');

            $this->mailService->notify(
                $to,
                $subject,
                $body,
                true
            );
        } catch (\Throwable $e) {
            error_log("[SendAccountDeletionEmail] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }
}
