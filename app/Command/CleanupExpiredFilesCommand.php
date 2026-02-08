<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\OutpassService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupExpiredFilesCommand extends Command
{
    protected static $defaultName = 'app:cleanup-expired-files';

    public function __construct(
        private readonly OutpassService $outpassService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Cleans up files (PDFs, QR codes, attachments) for expired outpasses.')
            ->setHelp('This command removes storage files associated with expired outpasses to free up disk space.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Cleaning up files for expired outpasses...');

        try {
            // Remove documents and attachments for expired outpasses
            $this->outpassService->removeExpireOutpassFiles();

            $output->writeln('Expired outpass files cleaned up successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error occurred while cleaning up expired outpass files: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
