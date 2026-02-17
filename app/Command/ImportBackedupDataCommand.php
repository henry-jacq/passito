<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportBackedupDataCommand extends Command
{
    protected static $defaultName = 'app:import-backup';

    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Import data from a backup directory or zip file')
            ->setHelp('Dangerous operation. Existing data in restored tables will be replaced.')
            ->addArgument('backup', InputArgument::REQUIRED, 'Backup directory path or .zip file path')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Confirm import')
            ->addOption('no-files', null, InputOption::VALUE_NONE, 'Do not import files from backup');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$input->getOption('force')) {
            $io->error('Import aborted. Re-run with --force.');
            return Command::FAILURE;
        }

        $backupInput = (string) $input->getArgument('backup');
        $importFiles = !$input->getOption('no-files');

        try {
            $workingDir = $this->prepareBackupDirectory($backupInput);
            $databaseJson = $workingDir . '/database.json';
            if (!is_file($databaseJson)) {
                throw new \RuntimeException("database.json not found in backup: {$workingDir}");
            }

            $decoded = json_decode((string) file_get_contents($databaseJson), true);
            if (!is_array($decoded) || !isset($decoded['tables']) || !is_array($decoded['tables'])) {
                throw new \RuntimeException('Invalid backup format: tables payload missing.');
            }

            $restored = $this->restoreTables($decoded['tables']);
            $filesImported = 0;
            if ($importFiles && is_dir($workingDir . '/files')) {
                $filesImported = $this->restoreFiles($workingDir . '/files');
            }

            $io->success('Backup import completed.');
            $io->table(
                ['Metric', 'Value'],
                [
                    ['Tables restored', (string) count($restored)],
                    ['Files imported', (string) $filesImported],
                    ['Source', $backupInput],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Backup import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function prepareBackupDirectory(string $backup): string
    {
        $resolved = realpath($backup);
        if ($resolved === false) {
            throw new \RuntimeException("Backup path not found: {$backup}");
        }

        if (is_dir($resolved)) {
            return $resolved;
        }

        if (!str_ends_with(strtolower($resolved), '.zip')) {
            throw new \RuntimeException('Backup must be a directory or .zip file.');
        }

        if (!class_exists(\ZipArchive::class)) {
            throw new \RuntimeException('ZipArchive extension is not available.');
        }

        $extractDir = STORAGE_PATH . '/backups/_import_' . date('Ymd_His');
        if (!is_dir($extractDir) && !mkdir($extractDir, 0775, true) && !is_dir($extractDir)) {
            throw new \RuntimeException("Unable to create extraction directory: {$extractDir}");
        }

        $zip = new \ZipArchive();
        if ($zip->open($resolved) !== true) {
            throw new \RuntimeException("Unable to open zip backup: {$resolved}");
        }
        $zip->extractTo($extractDir);
        $zip->close();

        return $extractDir;
    }

    private function restoreTables(array $tables): array
    {
        $conn = $this->em->getConnection();
        $schemaManager = $conn->createSchemaManager();
        $existingTables = array_flip($schemaManager->listTableNames());
        $restored = [];

        try {
            $conn->beginTransaction();
            $this->setForeignKeyChecks($conn, false);

            foreach (array_keys($tables) as $table) {
                if (!isset($existingTables[$table])) {
                    continue;
                }
                $conn->executeStatement("DELETE FROM {$table}");
            }

            foreach ($tables as $table => $rows) {
                if (!isset($existingTables[$table])) {
                    continue;
                }

                $count = 0;
                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        if (!is_array($row) || empty($row)) {
                            continue;
                        }
                        $conn->insert($table, $this->normalizeRow($row));
                        $count++;
                    }
                }

                $restored[$table] = $count;
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
            throw $e;
        }

        return $restored;
    }

    private function normalizeRow(array $row): array
    {
        foreach ($row as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $row[$key] = json_encode($value, JSON_UNESCAPED_SLASHES);
            }
        }

        return $row;
    }

    private function restoreFiles(string $filesDir): int
    {
        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($filesDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $item) {
            if ($item->isDir()) {
                continue;
            }

            $relative = substr($item->getPathname(), strlen($filesDir) + 1);
            $destination = ROOT_PATH . '/' . $relative;
            $destinationDir = dirname($destination);
            if (!is_dir($destinationDir)) {
                mkdir($destinationDir, 0775, true);
            }

            if (copy($item->getPathname(), $destination)) {
                $count++;
            }
        }

        return $count;
    }

    private function setForeignKeyChecks(\Doctrine\DBAL\Connection $conn, bool $enabled): void
    {
        $driver = strtolower((string) $conn->getDatabasePlatform()->getName());
        if (str_contains($driver, 'mysql')) {
            $conn->executeStatement('SET FOREIGN_KEY_CHECKS=' . ($enabled ? '1' : '0'));
        }
    }
}

