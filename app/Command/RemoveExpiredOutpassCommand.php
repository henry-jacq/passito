<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\OutpassService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveExpiredOutpassCommand extends Command
{
    protected static $defaultName = 'app:remove-expired-outpass';

    public function __construct(
        private readonly OutpassService $outpassService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Removes expired outpass requests and attachments.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Removing expired outpass requests...');

        try {
            // Remove expired outpass requests
            $this->outpassService->checkAndExpireOutpass();

            $output->writeln('Expired outpass requests removed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>Error occurred while removing expired outpass requests: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
