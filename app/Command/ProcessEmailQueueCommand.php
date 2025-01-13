<?php

declare(strict_types=1);

namespace App\Command;

use Cron\CronExpression;
use App\Services\MailService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessEmailQueueCommand extends Command
{
    protected static $defaultName = 'app:process-email-queue';

    public function __construct(
        private readonly MailService $mailService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Processes the email queue based on the cron schedule.')
            ->setHelp('This command checks the email queue and processes any pending emails at scheduled intervals.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Define a cron schedule (e.g., every 5 minutes)
        $cronExpression = new CronExpression('*/5 * * * *');

        // Check if the current time matches the cron schedule
        if ($cronExpression->isDue()) {
            $output->writeln('Processing email queue...');

            try {
                // Process email queue via the MailService
                $this->mailService->processQueue();

                $output->writeln('Email queue processing completed successfully.');
                return Command::SUCCESS;
            } catch (\Exception $e) {
                $output->writeln('<error>Error occurred while processing the email queue: ' . $e->getMessage() . '</error>');
                return Command::FAILURE;
            }
        }

        $output->writeln('Cron schedule does not match the current time, skipping...');
        return Command::SUCCESS;
    }
}
