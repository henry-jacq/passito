<?php

declare(strict_types=1);

namespace App\Command;

use App\Enum\UserRole;
use App\Seeders\AppSettingsSeeder;
use App\Seeders\OutpassTemplateSeeder;
use App\Seeders\ReportConfigSeeder;
use App\Services\OutpassService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FactoryResetCommand extends Command
{
    protected static $defaultName = 'app:factory-reset';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly OutpassService $outpassService
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Reset application data to initial state')
            ->setHelp('Dangerous operation. Use --force to execute.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Confirm factory reset')
            ->addOption('drop-super-admins', null, InputOption::VALUE_NONE, 'Also remove super admin users')
            ->addOption('keep-reference', null, InputOption::VALUE_NONE, 'Keep reference data (institutions/hostels/programs/templates/settings)')
            ->addOption('no-reseed', null, InputOption::VALUE_NONE, 'Do not reseed default settings/templates/report configs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$input->getOption('force')) {
            $io->error('Factory reset aborted. Re-run with --force.');
            return Command::FAILURE;
        }

        $keepSuperAdmins = !$input->getOption('drop-super-admins');
        $resetReferenceData = !$input->getOption('keep-reference');
        $reseedDefaults = !$input->getOption('no-reseed');
        $removedStorageEntries = 0;

        $conn = $this->em->getConnection();
        $deleted = [];

        try {
            $io->section('Resetting database data');
            $conn->beginTransaction();
            $this->setForeignKeyChecks($conn, false);

            $tablesToClear = [
                'jobs',
                'file_access_logs',
                'password_reset_tokens',
                'login_sessions',
                'parent_verifications',
                'logbook',
                'outpass_requests',
                'files',
                'verifiers',
                'students',
                'warden_assignments',
            ];

            foreach ($tablesToClear as $table) {
                $deleted[$table] = $conn->executeStatement("DELETE FROM {$table}");
            }

            if ($keepSuperAdmins) {
                $deleted['users'] = $conn->executeStatement(
                    'DELETE FROM users WHERE role <> :role',
                    ['role' => UserRole::SUPER_ADMIN->value]
                );
            } else {
                $deleted['users'] = $conn->executeStatement('DELETE FROM users');
            }

            if ($resetReferenceData) {
                $referenceTables = [
                    'report_configs',
                    'outpass_template_fields',
                    'outpass_templates',
                    'system_settings',
                    'institution_programs',
                    'academic_years',
                    'hostels',
                    'institutions',
                ];

                foreach ($referenceTables as $table) {
                    $deleted[$table] = $conn->executeStatement("DELETE FROM {$table}");
                }
            }

            $this->setForeignKeyChecks($conn, true);
            $conn->commit();
        } catch (\Throwable $e) {
            try {
                $this->setForeignKeyChecks($conn, true);
            } catch (\Throwable) {
            }
            try {
                $conn->rollBack();
            } catch (\Throwable) {
            }

            $io->error('Factory reset failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->em->clear();
        $removedStorageEntries = $this->clearStorageData($io);

        if ($reseedDefaults) {
            $io->section('Reseeding default configuration');
            (new AppSettingsSeeder($this->em))->run();
            (new OutpassTemplateSeeder($this->em, $this->outpassService))->run();
            (new ReportConfigSeeder($this->em))->run();
        }

        $io->success('Factory reset completed.');
        $io->table(
            ['Option', 'Value'],
            [
                ['Keep super admins', $keepSuperAdmins ? 'yes' : 'no'],
                ['Reset reference data', $resetReferenceData ? 'yes' : 'no'],
                ['Reseed defaults', $reseedDefaults ? 'yes' : 'no'],
                ['Storage entries removed', (string) $removedStorageEntries],
            ]
        );

        return Command::SUCCESS;
    }

    private function clearStorageData(SymfonyStyle $io): int
    {
        if (!is_dir(STORAGE_PATH)) {
            return 0;
        }

        $preserveFiles = ['.gitkeep', '.gitignore'];
        $paths = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(STORAGE_PATH, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if (!$item instanceof \SplFileInfo) {
                continue;
            }

            if (in_array($item->getFilename(), $preserveFiles, true)) {
                continue;
            }

            $paths[] = $item->getPathname();
        }

        if ($paths === []) {
            return 0;
        }

        $io->section('Removing storage data');
        $progress = $io->createProgressBar(count($paths));
        $progress->start();

        $removed = 0;
        foreach ($paths as $path) {
            $ok = is_dir($path) ? @rmdir($path) : @unlink($path);
            if ($ok) {
                $removed++;
            }
            $progress->advance();
        }

        $progress->finish();
        $io->newLine(2);

        return $removed;
    }

    private function setForeignKeyChecks(\Doctrine\DBAL\Connection $conn, bool $enabled): void
    {
        $driver = strtolower((string) $conn->getDatabasePlatform()->getName());
        if (str_contains($driver, 'mysql')) {
            $conn->executeStatement('SET FOREIGN_KEY_CHECKS=' . ($enabled ? '1' : '0'));
        }
    }
}
