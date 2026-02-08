<?php

declare(strict_types=1);

namespace App\Command;

use App\Jobs\CleanupExpiredFiles;
use App\Core\JobDispatcher;
use App\Core\JobPayloadBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupExpiredFilesCommand extends Command
{
    protected static $defaultName = 'app:cleanup-expired-files';

    public function __construct(
        private readonly JobDispatcher $jobDispatcher
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dispatches cleanup job for expired outpass files.')
            ->setHelp('This command dispatches a job to remove storage files associated with expired outpasses.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Dispatching cleanup job for expired outpass files...');

        try {
            $payload = new JobPayloadBuilder();
            $payload->set('scheduled_at', (new \DateTimeImmutable())->format('Y-m-d H:i:s'));

            $job = $this->jobDispatcher->dispatch(
                CleanupExpiredFiles::class,
                $payload
            );

            $output->writeln("Job #{$job->getId()} dispatched successfully. Workers will process it.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error dispatching cleanup job: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
