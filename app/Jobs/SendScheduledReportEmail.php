<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Entity\ReportConfig;
use App\Entity\User;
use App\Enum\UserRole;
use App\Interfaces\JobInterface;
use App\Services\MailService;
use App\Services\ReportService;
use App\Core\View;
use App\Core\Config;
use Doctrine\ORM\EntityManagerInterface;

class SendScheduledReportEmail implements JobInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ReportService $reportService,
        private readonly MailService $mailService,
        private readonly View $view,
        private readonly Config $config
    ) {}

    public function handle(array $payload): void
    {
        try {
            $reportConfigId = (int) ($payload['report_config_id'] ?? 0);
            if ($reportConfigId <= 0) {
                throw new \InvalidArgumentException(
                    'Invalid payload for SendScheduledReportEmail ' . json_encode($payload)
                );
            }

            /** @var ReportConfig|null $config */
            $config = $this->em->getRepository(ReportConfig::class)->find($reportConfigId);
            if (!$config instanceof ReportConfig || !$config->getIsEnabled()) {
                return;
            }

            $recipientIds = array_values(array_unique(array_map(
                'intval',
                is_array($payload['recipient_ids'] ?? null) ? $payload['recipient_ids'] : []
            )));

            $recipients = [];
            foreach ($recipientIds as $recipientId) {
                if ($recipientId <= 0) {
                    continue;
                }

                $user = $this->em->getRepository(User::class)->find($recipientId);
                if ($user instanceof User && filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = $user;
                }
            }

            if ($recipients === []) {
                return;
            }

            $reportContextUser = $this->resolveReportContextUser($config, $recipients);
            if (!$reportContextUser instanceof User) {
                throw new \RuntimeException('Unable to resolve report context user for scheduled email');
            }

            $generatedAt = new \DateTimeImmutable();
            $csvPath = $this->reportService->generateReport($reportContextUser, $config);

            $subject = sprintf(
                '%s - %s',
                $config->getReportKey()->label(),
                $generatedAt->format('d M Y')
            );

            $scheduledForRaw = (string) ($payload['scheduled_for'] ?? '');
            $scheduledFor = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $scheduledForRaw) ?: null;

            $body = $this->view->renderEmail('reports/scheduled', [
                'report' => $config,
                'generated_at' => $generatedAt,
                'scheduled_for' => $scheduledFor,
                'has_data' => $csvPath !== null,
                'template_variant' => (string) ($payload['template_variant'] ?? ($csvPath === null ? 'alert' : 'simple')),
                'app_name' => (string) ($this->config->get('app.name') ?? 'Passito'),
                'logo_url' => (string) ($this->config->get('app.logo') ?? ''),
                'cta_url' => rtrim((string) ($this->config->get('app.host') ?? ''), '/') . '/admin/dashboard',
            ]);

            $attachments = $csvPath !== null ? [$csvPath] : null;
            $sentAny = false;
            $failedRecipients = [];

            foreach ($recipients as $recipient) {
                $sent = $this->mailService->notify(
                    (string) $recipient->getEmail(),
                    $subject,
                    $body,
                    true,
                    $attachments
                );

                if ($sent) {
                    $sentAny = true;
                } else {
                    $failedRecipients[] = (string) $recipient->getEmail();
                }
            }

            if (!$sentAny) {
                throw new \RuntimeException('Failed to send scheduled report email to any recipient');
            }

            if ($sentAny) {
                $config->setLastSent($generatedAt);
                $config->setUpdatedAt(new \DateTime());
                $this->em->persist($config);
                $this->em->flush();
            }

            if ($failedRecipients !== []) {
                error_log('[SendScheduledReportEmail] Partial delivery failure for recipients: ' . implode(', ', $failedRecipients));
            }
        } catch (\Throwable $e) {
            error_log('[SendScheduledReportEmail] ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            throw $e;
        }
    }

    private function resolveReportContextUser(ReportConfig $config, array $recipients): ?User
    {
        foreach ($recipients as $recipient) {
            if ($recipient->getGender() === $config->getGender()) {
                return $recipient;
            }
        }

        foreach ($config->getRecipients() as $recipient) {
            if ($recipient instanceof User && $recipient->getGender() === $config->getGender()) {
                return $recipient;
            }
        }

        return $this->em->getRepository(User::class)->findOneBy([
            'role' => UserRole::SUPER_ADMIN,
            'gender' => $config->getGender(),
        ]);
    }
}
