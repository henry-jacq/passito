<?php

namespace App\Jobs;

use App\Core\View;
use App\Core\Config;
use App\Services\MailService;
use App\Interfaces\JobInterface;
use App\Services\UserService;

class SendAccountCreationEmail implements JobInterface
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
            $user = null;

            $userIdRaw = $payload['user_id'] ?? null;
            if (is_numeric($userIdRaw) && (int) $userIdRaw > 0) {
                $user = $this->userService->getUserById((int) $userIdRaw);
                if (!$user) {
                    // Non-retryable: the referenced user no longer exists.
                    error_log("[SendAccountCreationEmail] User not found (user_id={$userIdRaw}); skipping job.");
                    return;
                }
            }

            if (!$user) {
                $lookupEmail = (string) ($payload['email'] ?? $payload['to'] ?? '');
                if ($lookupEmail !== '') {
                    $user = $this->userService->getUserByEmail($lookupEmail);
                }
            }

            if (!$user) {
                throw new \InvalidArgumentException(
                    'Invalid payload for SendAccountCreationEmail ' . json_encode($payload)
                );
            }

            $host = rtrim((string) $this->config->get('app.host'), '/');
            $loginUrl = $host . $this->view->urlFor('auth.login');
            $forgotUrl = $host . $this->view->urlFor('auth.forgot');

            $to = (string) ($payload['to'] ?? $user->getEmail());
            $name = (string) ($payload['name'] ?? $user->getName());
            $defaultPassword = (string) ($payload['default_password'] ?? $user->getContactNo());

            $body = $this->view->renderEmail('auth/account_created', [
                'name' => $name,
                'email' => $to,
                'login_url' => $loginUrl,
                'forgot_url' => $forgotUrl,
                'app_name' => (string) ($this->config->get('app.name')),
                'default_password' => $defaultPassword,
            ]);

            $subject = 'Your Passito account is ready';
            $this->mailService->notify($to, $subject, $body, true);

        } catch (\Throwable $e) {
            error_log("[SendAccountCreationEmail] " . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }
}
