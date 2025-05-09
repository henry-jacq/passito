<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\OutpassService;
use App\Seeders\AppSettingsSeeder;
use App\Seeders\OutpassDataSeeder;
use App\Seeders\OutpassRulesSeeder;
use App\Seeders\OutpassTemplateSeeder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseSeederCommand extends Command
{
    protected static $defaultName = 'app:seed';

    private array $seeders = [
        'app_settings' => AppSettingsSeeder::class,
        'outpass_rules' => OutpassRulesSeeder::class,
        'outpass_data' => OutpassDataSeeder::class,
        'outpass_templates' => OutpassTemplateSeeder::class,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OutpassService $outpassService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Runs all seeders or a specific one if provided.')
            ->setHelp('Use: app:seed [seeder_name] [extra_param: studentId for outpass_data]')
            ->addArgument('seeder', InputArgument::OPTIONAL, 'The specific seeder to run.')
            ->addArgument('extra', InputArgument::OPTIONAL, 'Extra param: studentId for outpass_data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $seederKey = $input->getArgument('seeder');
        $extra = $input->getArgument('extra');

        if ($seederKey) {
            if (!array_key_exists($seederKey, $this->seeders)) {
                $io->error("Seeder '$seederKey' not found. Available: " . implode(', ', array_keys($this->seeders)));
                return Command::FAILURE;
            }

            $values = $this->resolveExtraParam($seederKey, $extra, $io);
            if ($values === null) {
                return Command::FAILURE;
            }

            return $this->runSeeder($input, $output, $this->seeders[$seederKey], $values);
        }

        $io->title('Running all seeders...');
        foreach ($this->seeders as $key => $seederClass) {
            $io->section("Seeding: $key");

            $values = $this->resolveExtraParam($key, $extra, $io);
            if ($values === null && $key === 'outpass_data') {
                return Command::FAILURE;
            }

            $result = $this->runSeeder($input, $output, $seederClass, $values ?? []);
            if ($result === Command::FAILURE) {
                return Command::FAILURE;
            }
        }

        $io->success('All seeders executed successfully!');
        return Command::SUCCESS;
    }

    private function resolveExtraParam(string $seederKey, ?string $extra, SymfonyStyle $io): ?array
    {
        if ($seederKey === 'outpass_data') {
            if ($extra === null || !ctype_digit($extra)) {
                $io->error("A valid numeric student ID is required for 'outpass_data' seeder.");
                return null;
            }
            return ['studentId' => (int)$extra];
        }

        return [];
    }

    private function runSeeder(InputInterface $input, OutputInterface $output, string $seederClass, array $values = []): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $seeder = match ($seederClass) {
                OutpassDataSeeder::class => new OutpassDataSeeder($this->entityManager, $values['studentId']),
                OutpassTemplateSeeder::class => new OutpassTemplateSeeder($this->entityManager, $this->outpassService),
                default => new $seederClass($this->entityManager),
            };

            $seeder->run();
            
            $io->success("$seederClass completed successfully.");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error("Error in $seederClass: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
