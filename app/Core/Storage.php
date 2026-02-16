<?php

namespace App\Core;

use Throwable;
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
    private bool $isLocal;
    private ?string $localRoot;

    /**
     * Constructor.
     *
     * @param FilesystemOperator $filesystem A Flysystem filesystem instance.
     */
    public function __construct(FilesystemOperator $filesystem, bool $isLocal = true, ?string $localRoot = null)
    {
        $this->filesystem = $filesystem;
        $this->isLocal = $isLocal;
        $this->localRoot = $localRoot;
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
     * Write stream to storage.
     *
     * @param resource $stream
     */
    public function writeStream(string $path, $stream): void
    {
        $this->filesystem->writeStream($path, $stream);
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
     * Read stream from storage.
     *
     * @return resource|null
     */
    public function readStream(string $path)
    {
        try {
            return $this->filesystem->readStream($path);
        } catch (Throwable $e) {
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
        $normalized = trim($directory, '/');
        if ($normalized === '') {
            return;
        }

        $this->filesystem->createDirectory($normalized);

        // Apply local permissions only when local adapter is used.
        if ($this->isLocal()) {
            $fullPath = $this->getFullPath($normalized);
            if (is_dir($fullPath)) {
                @chmod($fullPath, $permissions | 02000);
            }
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
    public function generateFileName(string $directory, string $extension, ?string $prefix = null): string
    {
        $this->ensureDirectoryExists($directory);

        $retry = 0;
        $maxRetries = 5;
        do {
            $randomName = substr(md5(microtime(true) . random_int(1000, 9999)), 0, 16);

            $name = $prefix
                ? $prefix . '_' . $randomName  // add underscore for readability
                : $randomName;

            $fileName = $name . '.' . ltrim($extension, '.');
            $path = rtrim($directory, '/') . '/' . $fileName;

            $retry++;
        } while ($this->fileExists($path) && $retry < $maxRetries);

        if ($this->fileExists($path)) {
            throw new \RuntimeException("Failed to generate unique file name after {$maxRetries} retries.");
        }

        return $path; // full path
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
        if (!$this->isLocal()) {
            throw new \RuntimeException('getFullPath() is available only for local filesystem adapters.');
        }

        if ($this->localRoot === null || $this->localRoot === '') {
            throw new \RuntimeException('Local storage root is not configured.');
        }

        $fullPath = rtrim($this->localRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($relativePath, DIRECTORY_SEPARATOR);

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
        if (!is_file($source) || !is_readable($source)) {
            throw new \RuntimeException("Uploaded source file is not readable.");
        }

        if ($this->isLocal() && is_uploaded_file($source)) {
            if (!move_uploaded_file($source, $this->getFullPath($destination, true))) {
                throw new \RuntimeException("Failed to move uploaded file.");
            }
            return;
        }

        $stream = fopen($source, 'rb');
        if ($stream === false) {
            throw new \RuntimeException("Failed to open uploaded file stream.");
        }

        try {
            $this->writeStream($destination, $stream);
        } finally {
            fclose($stream);
        }
    }

    public function isLocal(): bool
    {
        return $this->isLocal;
    }

    public function fileSize(string $path): ?int
    {
        try {
            return (int) $this->filesystem->fileSize($path);
        } catch (Throwable $e) {
            return null;
        }
    }

    public function mimeType(string $path): ?string
    {
        try {
            return $this->filesystem->mimeType($path);
        } catch (Throwable $e) {
            return null;
        }
    }

    public function lastModified(string $path): ?int
    {
        try {
            return (int) $this->filesystem->lastModified($path);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Materialize a storage object into a temporary local file path.
     * Useful for integrations that require local file paths (mail attachments, libraries).
     */
    public function materializeToTemp(string $storagePath, string $prefix = 'storage_'): ?string
    {
        $stream = $this->readStream($storagePath);
        if ($stream === null) {
            return null;
        }

        $tmp = tempnam(sys_get_temp_dir(), $prefix);
        if ($tmp === false) {
            fclose($stream);
            return null;
        }

        $dest = fopen($tmp, 'wb');
        if ($dest === false) {
            fclose($stream);
            @unlink($tmp);
            return null;
        }

        try {
            stream_copy_to_stream($stream, $dest);
        } finally {
            fclose($stream);
            fclose($dest);
        }

        return $tmp;
    }
}
