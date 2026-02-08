<?php

declare(strict_types=1);

namespace App\Command;

use App\Core\Config;
use App\Services\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class JobHealthCheckCommand extends Command
{
    protected static $defaultName = 'jobs:health';
    protected static $defaultDescription = 'Check job queue health and worker status';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MailService $mailService,
        private readonly Config $config
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Monitors job queue health and alerts if workers are down')
            ->setHelp('This command checks for stale jobs and alerts if workers appear to be down.')
            ->addOption('alert-threshold', null, InputOption::VALUE_OPTIONAL, 'Minutes before alerting on stale jobs', 5)
            ->addOption('exit-code-on-failure', null, InputOption::VALUE_NONE, 'Exit with code 1 if unhealthy (useful for monitoring)')
            ->addOption('send-email', null, InputOption::VALUE_NONE, 'Send email alert to admin if unhealthy');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->getFormatter()->setStyle('success', new OutputFormatterStyle('green', null, ['bold']));
        $output->getFormatter()->setStyle('warn', new OutputFormatterStyle('yellow', null, ['bold']));
        $output->getFormatter()->setStyle('error', new OutputFormatterStyle('red', null, ['bold']));

        $alertThreshold = (int) $input->getOption('alert-threshold');
        $exitOnFailure = $input->getOption('exit-code-on-failure');
        $sendEmail = $input->getOption('send-email');

        $output->writeln("<info>Job Queue Health Check</info>");
        $output->writeln(str_repeat('=', 50));

        // Get queue metrics
        $pendingJobs = $this->getPendingJobCount();
        $processingJobs = $this->getProcessingJobCount();
        $failedJobs = $this->getFailedJobCount();
        $staleJobs = $this->getStaleJobCount($alertThreshold);

        $output->writeln("Pending Jobs:    $pendingJobs");
        $output->writeln("Processing Jobs: $processingJobs");
        $output->writeln("Failed Jobs:     $failedJobs");
        $output->writeln("Stale Jobs:      $staleJobs (older than {$alertThreshold}m)");
        $output->writeln("");

        $isHealthy = true;

        // Check for stale jobs (workers might be down)
        if ($staleJobs > 0) {
            $output->writeln("<error>⚠ WARNING: $staleJobs jobs are stale!</error>");
            $output->writeln("<error>This may indicate workers are down or overloaded.</error>");
            $isHealthy = false;
        }

        // Check for high failure rate
        $totalJobs = $this->getTotalJobCount();
        if ($totalJobs > 0) {
            $failureRate = ($failedJobs / $totalJobs) * 100;
            if ($failureRate > 10) {
                $output->writeln(sprintf("<warn>⚠ High failure rate: %.1f%%</warn>", $failureRate));
                $isHealthy = false;
            }
        }

        // Check for processing jobs stuck
        $stuckProcessingJobs = $this->getStuckProcessingJobs($alertThreshold);
        if ($stuckProcessingJobs > 0) {
            $output->writeln("<error>⚠ WARNING: $stuckProcessingJobs jobs stuck in 'processing' state!</error>");
            $output->writeln("<error>Workers may have crashed while processing these jobs.</error>");
            $isHealthy = false;
        }

        $output->writeln("");
        if ($isHealthy) {
            $output->writeln("<success>✓ Job queue is healthy</success>");
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>✗ Job queue has issues - check workers!</error>");
            $output->writeln("");
            $output->writeln("<info>Recommended actions:</info>");
            $output->writeln("  1. Check if supervisor is running: ps aux | grep 'jobs:supervisor'");
            $output->writeln("  2. Check worker logs for errors");
            $output->writeln("  3. Restart supervisor: php passito.php jobs:supervisor");
            
            // Send email alert if requested
            if ($sendEmail) {
                $this->sendAlertEmail($pendingJobs, $staleJobs, $stuckProcessingJobs, $failedJobs, $output);
            }
            
            return $exitOnFailure ? Command::FAILURE : Command::SUCCESS;
        }
    }

    private function sendAlertEmail(
        int $pendingJobs,
        int $staleJobs,
        int $stuckProcessingJobs,
        int $failedJobs,
        OutputInterface $output
    ): void {
        try {
            $adminEmail = $this->config->get('notification.admin_email');
            
            if (!$adminEmail) {
                $output->writeln("<warn>No admin email configured. Set 'notification.admin_email' in config.</warn>");
                return;
            }

            $appName = $this->config->get('app.name', 'Passito');
            $timestamp = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
            
            $subject = "[$appName] Job Queue Health Alert";
            
            $body = "
                <h2 style='color: #dc3545;'>⚠️ Job Queue Health Alert</h2>
                <p><strong>Time:</strong> $timestamp</p>
                <p>The job queue system has detected issues that require attention:</p>
                
                <table style='border-collapse: collapse; width: 100%; margin: 20px 0;'>
                    <tr style='background-color: #f8f9fa;'>
                        <th style='padding: 10px; border: 1px solid #dee2e6; text-align: left;'>Metric</th>
                        <th style='padding: 10px; border: 1px solid #dee2e6; text-align: left;'>Count</th>
                        <th style='padding: 10px; border: 1px solid #dee2e6; text-align: left;'>Status</th>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>Pending Jobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>$pendingJobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>ℹ️ Info</td>
                    </tr>
                    <tr style='background-color: " . ($staleJobs > 0 ? '#fff3cd' : '#f8f9fa') . ";'>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>Stale Jobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'><strong>$staleJobs</strong></td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>" . ($staleJobs > 0 ? '⚠️ Warning' : '✓ OK') . "</td>
                    </tr>
                    <tr style='background-color: " . ($stuckProcessingJobs > 0 ? '#f8d7da' : '#f8f9fa') . ";'>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>Stuck Processing Jobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'><strong>$stuckProcessingJobs</strong></td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>" . ($stuckProcessingJobs > 0 ? '🔴 Critical' : '✓ OK') . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>Failed Jobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>$failedJobs</td>
                        <td style='padding: 10px; border: 1px solid #dee2e6;'>ℹ️ Info</td>
                    </tr>
                </table>
                
                <h3>Possible Causes:</h3>
                <ul>
                    <li><strong>Workers are down:</strong> The job supervisor may have crashed or stopped</li>
                    <li><strong>Workers crashed:</strong> Individual workers may have encountered fatal errors</li>
                    <li><strong>System overload:</strong> Workers may be overwhelmed by job volume</li>
                    <li><strong>Database issues:</strong> Connection problems preventing job processing</li>
                </ul>
                
                <h3>Recommended Actions:</h3>
                <ol>
                    <li>Check if supervisor is running: <code>ps aux | grep 'jobs:supervisor'</code></li>
                    <li>Check systemd status: <code>systemctl status passito-supervisor</code></li>
                    <li>Review worker logs for errors</li>
                    <li>Restart supervisor if needed: <code>systemctl restart passito-supervisor</code></li>
                    <li>Check system resources (CPU, memory, disk space)</li>
                </ol>
                
                <hr style='margin: 20px 0;'>
                <p style='color: #6c757d; font-size: 12px;'>
                    This is an automated alert from the $appName job queue monitoring system.
                    To disable these alerts, remove the --send-email flag from the health check cron job.
                </p>
            ";
            
            $success = $this->mailService->notify($adminEmail, $subject, $body, true);
            
            if ($success) {
                $output->writeln("<success>Alert email sent to: $adminEmail</success>");
            } else {
                $output->writeln("<error>Failed to send alert email to: $adminEmail</error>");
            }
        } catch (\Exception $e) {
            $output->writeln("<error>Failed to send alert email: {$e->getMessage()}</error>");
        }
    }

    private function getPendingJobCount(): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j')
            ->where('j.status = :status')
            ->andWhere('j.availableAt <= :now')
            ->setParameter('status', 'pending')
            ->setParameter('now', new \DateTimeImmutable());

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getProcessingJobCount(): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j')
            ->where('j.status = :status')
            ->setParameter('status', 'processing');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getFailedJobCount(): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j')
            ->where('j.status = :status')
            ->setParameter('status', 'failed');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getTotalJobCount(): int
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j');

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getStaleJobCount(int $minutes): int
    {
        $threshold = new \DateTimeImmutable("-{$minutes} minutes");
        
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j')
            ->where('j.status = :status')
            ->andWhere('j.availableAt <= :threshold')
            ->setParameter('status', 'pending')
            ->setParameter('threshold', $threshold);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function getStuckProcessingJobs(int $minutes): int
    {
        $threshold = new \DateTimeImmutable("-{$minutes} minutes");
        
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(j.id)')
            ->from(\App\Entity\Job::class, 'j')
            ->where('j.status = :status')
            ->andWhere('j.createdAt <= :threshold')
            ->setParameter('status', 'processing')
            ->setParameter('threshold', $threshold);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}
