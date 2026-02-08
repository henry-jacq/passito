<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class JobSupervisorCommand extends Command
{
    protected static $defaultName = 'jobs:supervisor';
    protected static $defaultDescription = 'Supervises job queue and dynamically spawns/scales workers';

    private array $workers = [];
    private bool $shouldRun = true;

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Monitors job queue load and dynamically manages worker processes')
            ->setHelp('This supervisor monitors the job queue and spawns workers based on load, scaling up or down as needed.')
            ->addOption('min-workers', null, InputOption::VALUE_OPTIONAL, 'Minimum number of workers', 1)
            ->addOption('max-workers', null, InputOption::VALUE_OPTIONAL, 'Maximum number of workers', 10)
            ->addOption('scale-up-threshold', null, InputOption::VALUE_OPTIONAL, 'Jobs per worker to trigger scale up', 5)
            ->addOption('scale-down-threshold', null, InputOption::VALUE_OPTIONAL, 'Jobs per worker to trigger scale down', 2)
            ->addOption('check-interval', null, InputOption::VALUE_OPTIONAL, 'Seconds between load checks', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Define custom console styles
        $output->getFormatter()->setStyle('info', new OutputFormatterStyle('cyan'));
        $output->getFormatter()->setStyle('success', new OutputFormatterStyle('green', null, ['bold']));
        $output->getFormatter()->setStyle('warn', new OutputFormatterStyle('yellow', null, ['bold']));
        $output->getFormatter()->setStyle('error', new OutputFormatterStyle('red', null, ['bold']));

        $minWorkers = (int) $input->getOption('min-workers');
        $maxWorkers = (int) $input->getOption('max-workers');
        $scaleUpThreshold = (int) $input->getOption('scale-up-threshold');
        $scaleDownThreshold = (int) $input->getOption('scale-down-threshold');
        $checkInterval = (int) $input->getOption('check-interval');

        $output->writeln("<info>Job Supervisor started</info>");
        $output->writeln("<info>Configuration:</info>");
        $output->writeln("  Min Workers: $minWorkers");
        $output->writeln("  Max Workers: $maxWorkers");
        $output->writeln("  Scale Up Threshold: $scaleUpThreshold jobs/worker");
        $output->writeln("  Scale Down Threshold: $scaleDownThreshold jobs/worker");
        $output->writeln("  Check Interval: {$checkInterval}s");
        $output->writeln("");

        // Handle signals
        if (function_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
        }

        pcntl_signal(SIGINT, function () use ($output) {
            $output->writeln("\n<warn>Interrupt received. Shutting down supervisor...</warn>");
            $this->shouldRun = false;
        });

        pcntl_signal(SIGTERM, function () use ($output) {
            $output->writeln("\n<warn>Termination signal received. Shutting down supervisor...</warn>");
            $this->shouldRun = false;
        });

        // Start minimum workers
        $this->spawnWorkers($minWorkers, $output);

        // Main supervision loop
        while ($this->shouldRun) {
            // Clean up dead workers
            $this->cleanupDeadWorkers($output);

            // Get current queue metrics
            $pendingJobs = $this->getPendingJobCount();
            $activeWorkers = count($this->workers);

            $timestamp = (new \DateTimeImmutable())->format('H:i:s');
            $output->writeln("<info>[$timestamp] Queue: $pendingJobs jobs | Workers: $activeWorkers</info>");

            // Calculate jobs per worker
            $jobsPerWorker = $activeWorkers > 0 ? $pendingJobs / $activeWorkers : $pendingJobs;

            // Scale up if needed
            if ($jobsPerWorker > $scaleUpThreshold && $activeWorkers < $maxWorkers) {
                $workersToSpawn = min(
                    (int) ceil($pendingJobs / $scaleUpThreshold) - $activeWorkers,
                    $maxWorkers - $activeWorkers
                );
                
                if ($workersToSpawn > 0) {
                    $output->writeln("<success>Scaling UP: Spawning $workersToSpawn worker(s)</success>");
                    $this->spawnWorkers($workersToSpawn, $output);
                }
            }

            // Scale down if needed
            if ($jobsPerWorker < $scaleDownThreshold && $activeWorkers > $minWorkers) {
                $workersToStop = min(
                    $activeWorkers - max((int) ceil($pendingJobs / $scaleUpThreshold), $minWorkers),
                    $activeWorkers - $minWorkers
                );

                if ($workersToStop > 0) {
                    $output->writeln("<warn>Scaling DOWN: Stopping $workersToStop worker(s)</warn>");
                    $this->stopWorkers($workersToStop, $output);
                }
            }

            sleep($checkInterval);
        }

        // Graceful shutdown
        $output->writeln("<warn>Shutting down all workers...</warn>");
        $this->stopAllWorkers($output);
        $output->writeln("<success>Supervisor stopped gracefully.</success>");

        return Command::SUCCESS;
    }

    private function spawnWorkers(int $count, OutputInterface $output): void
    {
        for ($i = 0; $i < $count; $i++) {
            $pid = pcntl_fork();

            if ($pid === -1) {
                $output->writeln("<error>Failed to fork worker process</error>");
                continue;
            }

            if ($pid === 0) {
                // Child process - execute worker
                $this->executeWorker();
                exit(0);
            }

            // Parent process - track worker
            $this->workers[$pid] = [
                'pid' => $pid,
                'started_at' => time(),
            ];

            $output->writeln("<success>Spawned worker with PID: $pid</success>");
        }
    }

    private function executeWorker(): void
    {
        // Execute the actual worker command
        $phpBinary = PHP_BINARY;
        $script = $_SERVER['SCRIPT_FILENAME'] ?? 'passito.php';
        
        // Execute worker in same directory
        pcntl_exec($phpBinary, [$script, 'jobs:worker']);
    }

    private function stopWorkers(int $count, OutputInterface $output): void
    {
        $stopped = 0;
        
        foreach ($this->workers as $pid => $info) {
            if ($stopped >= $count) {
                break;
            }

            if (posix_kill($pid, SIGTERM)) {
                $output->writeln("<warn>Sent SIGTERM to worker PID: $pid</warn>");
                unset($this->workers[$pid]);
                $stopped++;
            }
        }
    }

    private function stopAllWorkers(OutputInterface $output): void
    {
        foreach ($this->workers as $pid => $info) {
            posix_kill($pid, SIGTERM);
            $output->writeln("<warn>Stopped worker PID: $pid</warn>");
        }

        // Wait for all workers to finish
        while (count($this->workers) > 0) {
            $this->cleanupDeadWorkers($output);
            usleep(100000); // 100ms
        }
    }

    private function cleanupDeadWorkers(OutputInterface $output): void
    {
        foreach ($this->workers as $pid => $info) {
            $status = null;
            $result = pcntl_waitpid($pid, $status, WNOHANG);

            if ($result === $pid || $result === -1) {
                // Worker has exited
                unset($this->workers[$pid]);
                
                if (pcntl_wifexited($status)) {
                    $exitCode = pcntl_wexitstatus($status);
                    if ($exitCode !== 0) {
                        $output->writeln("<error>Worker PID $pid exited with code: $exitCode</error>");
                    }
                } elseif (pcntl_wifsignaled($status)) {
                    $signal = pcntl_wtermsig($status);
                    $output->writeln("<warn>Worker PID $pid terminated by signal: $signal</warn>");
                }
            }
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
}
