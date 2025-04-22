<?php

declare(strict_types=1);

namespace App\Command;

use App\Seeders\AppSettingsSeeder;
use App\Seeders\OutpassDataSeeder;
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
        'outpass_data' => OutpassDataSeeder::class,
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
            ->addArgument('seeder', InputArgument::OPTIONAL, 'The name of the specific seeder to run (e.g., settings, users, institutions, hostels).')
            ->addArgument('studentId', InputArgument::OPTIONAL, 'Student ID for seeding outpass data).');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seederKey = $input->getArgument('seeder');
        $studentId = $input->getArgument('studentId') !== null ? (int)$input->getArgument('studentId') : null;

        if ($seederKey) {
            if (!array_key_exists($seederKey, $this->seeders)) {
                $output->writeln("<error>Seeder '$seederKey' not found. Available seeders: " . implode(', ', array_keys($this->seeders)) . "</error>");
                return Command::FAILURE;
            }

            return $this->runSeeder($this->seeders[$seederKey], $output, $studentId);
        }

        $output->writeln('<info>Running all seeders...</info>');
        foreach ($this->seeders as $key => $seederClass) {
            $output->writeln("<comment>Seeding: $key</comment>");
            $result = $this->runSeeder($seederClass, $output, $studentId);
            if ($result === Command::FAILURE) {
                return Command::FAILURE;
            }
        }

        $output->writeln('<info>All seeders executed successfully!</info>');
        return Command::SUCCESS;
    }

    private function runSeeder(string $seederClass, OutputInterface $output, ?int $studentId = null): int
    {
        try {
            if ($seederClass === OutpassDataSeeder::class) {
                if ($studentId === null) {
                    throw new \InvalidArgumentException("Student ID must be provided for outpass_data seeder.");
                }
                $seeder = new OutpassDataSeeder($this->entityManager, $studentId);
            } else {
                $seeder = new $seederClass($this->entityManager);
            }

            $seeder->run();
            $output->writeln("<info>{$seederClass} completed successfully.</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Error in {$seederClass}: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
