<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\ReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DispatchScheduledReportsCommand extends Command
{
    protected static $defaultName = 'app:dispatch-scheduled-reports';
    private const LOCK_FILE = STORAGE_PATH . '/locks/scheduled_reports_dispatch.lock';

    public function __construct(
        private readonly ReportService $reportService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dispatch due scheduled report emails into the jobs queue.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Show due schedule summary without dispatching jobs.')
            ->addOption(
                'at',
                null,
                InputOption::VALUE_OPTIONAL,
                'Evaluate schedules at this datetime (Y-m-d H:i:s). Defaults to current server time.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = (bool) $input->getOption('dry-run');
        $asOfInput = (string) ($input->getOption('at') ?? '');

        $asOf = new \DateTimeImmutable();
        if ($asOfInput !== '') {
            $parsed = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $asOfInput);
            if (!$parsed instanceof \DateTimeImmutable) {
                $io->error("Invalid --at value. Use format: Y-m-d H:i:s (example: 2026-02-17 09:30:00)");
                return Command::FAILURE;
            }
            $asOf = $parsed;
        }

        $lockHandle = $this->acquireLock();
        if (!is_resource($lockHandle)) {
            $io->warning('Scheduled report dispatcher is already running. Skipping this cycle.');
            return Command::SUCCESS;
        }

        try {
            $io->section('Scheduled Report Dispatch');
            $io->text('Evaluation time: ' . $asOf->format('Y-m-d H:i:s'));
            $io->text($dryRun ? 'Mode: dry-run' : 'Mode: dispatch to queue');

            $summary = $this->reportService->dispatchDueScheduledReports($asOf, $dryRun);

            $io->table(
                ['Metric', 'Value'],
                [
                    ['Configs checked', (string) $summary['checked']],
                    ['Initialized next send', (string) $summary['initialized']],
                    ['Due now', (string) $summary['due']],
                    ['Jobs dispatched', (string) $summary['dispatched']],
                    ['Skipped (no recipients)', (string) $summary['skipped_no_recipients']],
                ]
            );

            if ($dryRun) {
                $io->success('Dry-run completed.');
            } else {
                $io->success('Scheduled report jobs dispatched.');
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Failed dispatching scheduled reports: ' . $e->getMessage());
            return Command::FAILURE;
        } finally {
            $this->releaseLock($lockHandle);
        }
    }

    private function acquireLock()
    {
        $lockDir = dirname(self::LOCK_FILE);
        if (!is_dir($lockDir)) {
            @mkdir($lockDir, 0775, true);
        }

        $handle = @fopen(self::LOCK_FILE, 'c');
        if (!is_resource($handle)) {
            return null;
        }

        if (!@flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);
            return null;
        }

        return $handle;
    }

    /**
     * @param resource|null $handle
     */
    private function releaseLock($handle): void
    {
        if (!is_resource($handle)) {
            return;
        }

        @flock($handle, LOCK_UN);
        @fclose($handle);
    }
}
