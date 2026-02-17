<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BackupDataCommand extends Command
{
    protected static $defaultName = 'app:backup-data';

    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a backup of database and selected file directories')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Backup name')
            ->addOption('no-db', null, InputOption::VALUE_NONE, 'Skip database backup')
            ->addOption('no-files', null, InputOption::VALUE_NONE, 'Skip files backup')
            ->addOption('source', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'File source dir(s), relative to project root', ['storage', 'resources/assets'])
            ->addOption('no-zip', null, InputOption::VALUE_NONE, 'Do not create zip artifact');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $includeDb = !$input->getOption('no-db');
        $includeFiles = !$input->getOption('no-files');
        $sourceDirs = (array) $input->getOption('source');
        $createZip = !$input->getOption('no-zip');

        $timestamp = (new \DateTimeImmutable())->format('Ymd_His');
        $backupName = (string) ($input->getOption('name') ?: "backup_{$timestamp}");
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $backupName) ?: "backup_{$timestamp}";
        $backupRoot = STORAGE_PATH . '/backups/' . $safeName;

        if (!is_dir($backupRoot) && !mkdir($backupRoot, 0775, true) && !is_dir($backupRoot)) {
            $io->error("Unable to create backup directory: {$backupRoot}");
            return Command::FAILURE;
        }

        $summary = [
            'backup_name' => $safeName,
            'backup_dir' => $backupRoot,
            'tables' => [],
            'files' => [],
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];

        try {
            $io->section('Starting backup');

            if ($includeDb) {
                $io->text('Backing up database tables...');
                $summary['tables'] = $this->backupTables($backupRoot, $io);
            }

            if ($includeFiles) {
                $io->text('Backing up file sources (includes storage by default)...');
                $summary['files'] = $this->backupFiles($backupRoot, $sourceDirs, $io);
            }

            file_put_contents(
                $backupRoot . '/metadata.json',
                json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            if ($createZip) {
                $io->text('Creating zip artifact...');
                $zipPath = $this->createZip($backupRoot);
                if ($zipPath !== null) {
                    $summary['zip_file'] = $zipPath;
                }
            }

            $io->success('Backup completed.');
            $io->table(
                ['Key', 'Value'],
                [
                    ['Backup directory', $summary['backup_dir']],
                    ['Zip file', $summary['zip_file'] ?? '(disabled or unavailable)'],
                ]
            );

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $io->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function backupTables(string $backupRoot, SymfonyStyle $io): array
    {
        $conn = $this->em->getConnection();
        $schemaManager = $conn->createSchemaManager();
        $tableNames = $schemaManager->listTableNames();

        $data = [];
        $counts = [];
        $progress = $io->createProgressBar(count($tableNames));
        $progress->start();

        foreach ($tableNames as $table) {
            $rows = $conn->fetchAllAssociative("SELECT * FROM {$table}");
            $data[$table] = $rows;
            $counts[$table] = count($rows);
            $progress->advance();
        }

        $progress->finish();
        $io->newLine(2);

        file_put_contents(
            $backupRoot . '/database.json',
            json_encode(['tables' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $counts;
    }

    private function backupFiles(string $backupRoot, array $sourceDirs, SymfonyStyle $io): array
    {
        $filesRoot = $backupRoot . '/files';
        if (!is_dir($filesRoot) && !mkdir($filesRoot, 0775, true) && !is_dir($filesRoot)) {
            throw new \RuntimeException("Unable to create files backup directory: {$filesRoot}");
        }

        $resolvedBackupRoot = realpath($backupRoot);
        $excludedPaths = [$resolvedBackupRoot !== false ? $resolvedBackupRoot : $backupRoot];
        $summary = [];
        foreach ($sourceDirs as $rawSource) {
            $source = trim((string) $rawSource, '/');
            if ($source === '') {
                continue;
            }

            $absSource = ROOT_PATH . '/' . $source;
            if (!is_dir($absSource)) {
                $summary[$source] = 0;
                continue;
            }

            $absTarget = $filesRoot . '/' . $source;
            $io->text("Copying {$source} ...");
            $summary[$source] = $this->copyDirectory($absSource, $absTarget, $excludedPaths, $io);
        }

        return $summary;
    }

    private function copyDirectory(string $sourceDir, string $targetDir, array $excludedPaths, SymfonyStyle $io): int
    {
        $count = 0;
        $totalFiles = 0;
        $countIterator = $this->createFilteredIterator($sourceDir, $excludedPaths);
        foreach ($countIterator as $item) {
            if ($item->isFile()) {
                $totalFiles++;
            }
        }

        if ($totalFiles === 0) {
            return 0;
        }

        $progress = $io->createProgressBar($totalFiles);
        $progress->start();

        $iterator = $this->createFilteredIterator($sourceDir, $excludedPaths);

        foreach ($iterator as $item) {
            $relative = substr($item->getPathname(), strlen($sourceDir) + 1);
            $destination = $targetDir . '/' . $relative;

            if ($item->isDir()) {
                if (!is_dir($destination)) {
                    mkdir($destination, 0775, true);
                }
                continue;
            }

            $parent = dirname($destination);
            if (!is_dir($parent)) {
                mkdir($parent, 0775, true);
            }

            if (copy($item->getPathname(), $destination)) {
                $count++;
            }
            $progress->advance();
        }

        $progress->finish();
        $io->newLine(2);

        return $count;
    }

    private function createFilteredIterator(string $sourceDir, array $excludedPaths): \RecursiveIteratorIterator
    {
        $directoryIterator = new \RecursiveDirectoryIterator($sourceDir, \FilesystemIterator::SKIP_DOTS);
        $filter = new \RecursiveCallbackFilterIterator(
            $directoryIterator,
            function (\SplFileInfo $current) use ($excludedPaths): bool {
                $path = $current->getPathname();
                foreach ($excludedPaths as $excludedPath) {
                    if ($path === $excludedPath || str_starts_with($path, $excludedPath . DIRECTORY_SEPARATOR)) {
                        return false;
                    }
                }

                return true;
            }
        );

        return new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
    }

    private function createZip(string $backupRoot): ?string
    {
        if (!class_exists(\ZipArchive::class)) {
            return null;
        }

        $zipPath = $backupRoot . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($backupRoot, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }
            $filePath = $file->getPathname();
            $localPath = substr($filePath, strlen($backupRoot) + 1);
            $zip->addFile($filePath, $localPath);
        }

        $zip->close();
        return $zipPath;
    }
}
