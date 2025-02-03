<?php

namespace App\Core;

use League\Flysystem\FilesystemOperator;
use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCopyFile;
use League\Flysystem\UnableToMoveFile;

class Storage
{
    /**
     * The Flysystem filesystem instance.
     *
     * @var FilesystemOperator
     */
    protected FilesystemOperator $filesystem;

    /**
     * Constructor.
     *
     * @param FilesystemOperator $filesystem A Flysystem filesystem instance.
     */
    public function __construct(FilesystemOperator $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Writes content to a file at the given path.
     *
     * @param string $path     The path where the file will be written.
     * @param string $contents The content to write.
     *
     * @return void
     *
     * @throws FilesystemException If an error occurs during writing.
     */
    public function write(string $path, string $contents): void
    {
        $this->filesystem->write($path, $contents);
    }

    /**
     * Reads and returns the content of a file.
     *
     * @param string $path The path of the file to read.
     *
     * @return string|null The file contents or null if not found.
     *
     * @throws FilesystemException If an error occurs during reading.
     */
    public function read(string $path): ?string
    {
        try {
            return $this->filesystem->read($path);
        } catch (FilesystemException $e) {
            // Optionally log the error or handle it as needed.
            return null;
        }
    }

    /**
     * Deletes a file at the given path.
     *
     * @param string $path The path of the file to delete.
     *
     * @return void
     *
     * @throws FilesystemException If an error occurs during deletion.
     */
    public function delete(string $path): void
    {
        $this->filesystem->delete($path);
    }

    /**
     * Checks if a file exists at the given path.
     *
     * @param string $path The path to check.
     *
     * @return bool True if the file exists, false otherwise.
     */
    public function fileExists(string $path): bool
    {
        return $this->filesystem->fileExists($path);
    }

    /**
     * Copies a file from source to destination.
     *
     * @param string $source      The source file path.
     * @param string $destination The destination file path.
     *
     * @return void
     *
     * @throws UnableToCopyFile If the file cannot be copied.
     */
    public function copy(string $source, string $destination): void
    {
        $this->filesystem->copy($source, $destination);
    }

    /**
     * Moves a file from source to destination.
     *
     * @param string $source      The source file path.
     * @param string $destination The destination file path.
     *
     * @return void
     *
     * @throws UnableToMoveFile If the file cannot be moved.
     */
    public function move(string $source, string $destination): void
    {
        $this->filesystem->move($source, $destination);
    }

    /**
     * Lists all files within a given directory.
     *
     * @param string $directory The directory path to list files from.
     * @param bool   $recursive Whether to list files recursively.
     *
     * @return array An array of file paths.
     *
     * @throws FilesystemException If an error occurs during listing.
     */
    public function listFiles(string $directory = '', bool $recursive = false): array
    {
        $files = [];

        // listContents returns an iterable of FileAttributes or DirectoryAttributes
        $contents = $this->filesystem->listContents($directory, $recursive);

        foreach ($contents as $item) {
            // Check if the current item is a file (Flysystem v2 returns objects with isFile() and path() methods)
            if ($item->isFile()) {
                $files[] = $item->path();
            }
        }

        return $files;
    }

    /**
     * Ensures that a directory exists.
     *
     * If the directory does not exist, it will be created.
     *
     * @param string $directory The directory path.
     *
     * @return void
     */
    public function ensureDirectoryExists(string $directory, int $permissions = 0775): void
    {
        if (!$this->filesystem->directoryExists($directory)) {
            $this->filesystem->createDirectory($directory);

            // Get the absolute path (you may need to adjust this depending on your setup)
            $fullPath = $this->getFullPath($directory);

            // Set permissions after creation
            chmod($fullPath, $permissions | 02000);
        }
    }

    /**
     * Generates a unique file name within the specified directory.
     *
     * @param string $directory The target directory (relative path).
     * @param string $extension The file extension (without the dot).
     *
     * @return string The unique file path.
     *
     * @throws \Exception If a unique file name cannot be generated.
     */
    public function generateFileName(string $directory, string $extension): string
    {
        // Ensure that the target directory exists.
        $this->ensureDirectoryExists($directory);

        $retry = 0;
        $maxRetries = 5;
        do {
            // Generate a pseudo-random file name
            $fileName = substr(md5(microtime(true) . random_int(1000, 9999)), 0, 16) . ".{$extension}";
            $path = rtrim($directory, '/') . '/' . $fileName;
            $retry++;
        } while ($this->fileExists($path) && $retry < $maxRetries);

        if ($this->fileExists($path)) {
            throw new \Exception("Failed to generate unique file name after {$maxRetries} retries.");
        }

        return $path;
    }

    /**
     * Remove file from storage.
     */
    public function removeFile(string $filePath): bool
    {
        if ($this->fileExists($filePath)) {
            try {
                $this->delete($filePath);
                return true;
            } catch (\Exception $e) {
                error_log("Failed to remove file: {$filePath} Error: " . $e->getMessage());
            }
        }

        return false;
    }

    /**
     * Get the full system path for a given relative storage path.
     *
     *
     * @param string $relativePath The relative file path.
     * @param bool   $createDir    Whether to ensure the directory exists.
     * @param int    $permissions  Permissions to apply when creating directories.
     *
     * @return string The full system path.
     *
     * @throws \RuntimeException If the directory cannot be created.
     */
    public function getFullPath(string $relativePath, bool $createDir = false, int $permissions = 0775): string
    {
        // Ensure STORAGE_PATH is defined in your application
        if (!defined('STORAGE_PATH')) {
            throw new \RuntimeException('STORAGE_PATH constant is not defined.');
        }

        // Construct the full path by combining STORAGE_PATH and the relative path.
        $fullPath = rtrim(STORAGE_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);

        if ($createDir) {
            // Determine the directory that should exist.
            $dirPath = is_dir($fullPath) ? $fullPath : dirname($fullPath);

            // If the directory doesn't exist, attempt to create it recursively.
            if (!is_dir($dirPath)) {
                if (!mkdir($dirPath, $permissions | 02000, true)) {
                    throw new \RuntimeException("Failed to create directory: $dirPath");
                }
            }
            // Apply the group sticky bit explicitly.
            chmod($dirPath, $permissions | 02000);
        }

        return $fullPath;
    }

    /**
     * Move an uploaded file to a new location.
     */
    public function moveUploadedFile(string $source, string $destination): void
    {
        if (!move_uploaded_file($source, $this->getFullPath($destination, true))) {
            throw new \RuntimeException("Failed to move uploaded file.");
        }
    }
}
