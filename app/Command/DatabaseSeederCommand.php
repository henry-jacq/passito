<?php

declare(strict_types=1);

namespace App\Command;

use App\Seeders\AppSettingsSeeder;
use App\Seeders\OutpassRulesSeeder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSeederCommand extends Command
{
    protected static $defaultName = 'app:seed';

    private array $seeders = [
        'app_settings' => AppSettingsSeeder::class,
        'outpass_rules' => OutpassRulesSeeder::class,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Runs all seeders or a specific one if provided.')
            ->setHelp('Run this command to seed the database with initial data. Use an argument to seed a specific table.')
            ->addArgument('seeder', InputArgument::OPTIONAL, 'The name of the specific seeder to run (e.g., settings, users, institutions, hostels).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seederKey = $input->getArgument('seeder');

        if ($seederKey) {
            // Run a specific seeder
            if (!array_key_exists($seederKey, $this->seeders)) {
                $output->writeln("<error>Seeder '$seederKey' not found. Available seeders: " . implode(', ', array_keys($this->seeders)) . "</error>");
                return Command::FAILURE;
            }

            return $this->runSeeder($this->seeders[$seederKey], $output);
        }

        // Run all seeders
        $output->writeln('<info>Running all seeders...</info>');
        foreach ($this->seeders as $key => $seederClass) {
            $output->writeln("<comment>Seeding: $key</comment>");
            $result = $this->runSeeder($seederClass, $output);
            if ($result === Command::FAILURE) {
                return Command::FAILURE;
            }
        }

        $output->writeln('<info>All seeders executed successfully!</info>');
        return Command::SUCCESS;
    }

    private function runSeeder(string $seederClass, OutputInterface $output): int
    {
        try {
            $seeder = new $seederClass($this->entityManager);
            $seeder->run();
            $output->writeln("<info>{$seederClass} completed successfully.</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error in {$seederClass}: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
