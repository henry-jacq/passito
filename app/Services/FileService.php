<?php

namespace App\Services;

use DateTime;
use App\Core\Storage;
use App\Entity\User;
use App\Entity\StoredFile;
use App\Enum\UserRole;
use App\Enum\ResourceType;
use App\Enum\ResourceVisibility;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\UploadedFileInterface;
use Ramsey\Uuid\Uuid;

class FileService
{
    public function __construct(
        private readonly Storage $storage,
        private readonly EntityManagerInterface $em,
        private readonly SecureLinkService $links
    ) {
    }

    public function isUuid(string $value): bool
    {
        return Uuid::isValid($value);
    }

    public function getByUuid(string $uuid): ?StoredFile
    {
        return $this->em->getRepository(StoredFile::class)->findOneBy(['uuid' => $uuid]);
    }

    public function getOriginalNameByUuid(string $uuid): ?string
    {
        $file = $this->getByUuid($uuid);
        return $file?->getOriginalName();
    }

    public function generateToken(StoredFile $file): string
    {
        return $this->links->generateToken('file', $file->getUuid());
    }

    public function canAccess(StoredFile $file, ?User $user): bool
    {
        $visibility = $file->getVisibility();

        if ($visibility === ResourceVisibility::PUBLIC) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if (UserRole::isAdministrator($user->getRole()->value)) {
            return true;
        }

        if ($visibility === ResourceVisibility::ADMIN) {
            return false;
        }

        if ($visibility === ResourceVisibility::OWNER || $visibility === ResourceVisibility::PRIVATE) {
            $owner = $file->getOwnerUser();
            return $owner !== null && $owner->getId() === $user->getId();
        }

        return false;
    }

    public function createFromUpload(
        UploadedFileInterface $file,
        string $directory,
        ResourceType $resourceType,
        ?User $ownerUser = null,
        ?int $resourceId = null,
        ResourceVisibility $visibility = ResourceVisibility::OWNER
    ): StoredFile {
        $originalName = $file->getClientFilename() ?: 'file';
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $tempPath = $file->getStream()->getMetadata('uri');

        $storagePath = $this->storage->generateFileName($directory, $extension);
        $this->storage->moveUploadedFile($tempPath, $storagePath);

        $mimeType = $file->getClientMediaType();
        if (!$mimeType) {
            $fullPath = $this->storage->getFullPath($storagePath);
            $mimeType = is_file($fullPath) ? (mime_content_type($fullPath) ?: 'application/octet-stream') : 'application/octet-stream';
        }

        $stored = new StoredFile();
        $stored->setUuid(Uuid::uuid4()->toString());
        $stored->setStoragePath($storagePath);
        $stored->setOriginalName($originalName);
        $stored->setMimeType($mimeType);
        $stored->setSize((int) ($file->getSize() ?? 0));
        $stored->setResourceType($resourceType);
        $stored->setResourceId($resourceId);
        $stored->setVisibility($visibility);
        $stored->setOwnerUser($ownerUser);
        $stored->setCreatedAt(new DateTime());

        $this->em->persist($stored);
        $this->em->flush();

        return $stored;
    }

    public function registerStoredFile(
        string $storagePath,
        string $originalName,
        string $mimeType,
        int $size,
        ResourceType $resourceType,
        ?User $ownerUser = null,
        ?int $resourceId = null,
        ResourceVisibility $visibility = ResourceVisibility::OWNER
    ): StoredFile {
        $stored = new StoredFile();
        $stored->setUuid(Uuid::uuid4()->toString());
        $stored->setStoragePath($storagePath);
        $stored->setOriginalName($originalName);
        $stored->setMimeType($mimeType ?: 'application/octet-stream');
        $stored->setSize($size);
        $stored->setResourceType($resourceType);
        $stored->setResourceId($resourceId);
        $stored->setVisibility($visibility);
        $stored->setOwnerUser($ownerUser);
        $stored->setCreatedAt(new DateTime());

        $this->em->persist($stored);
        $this->em->flush();

        return $stored;
    }

    public function updateResourceId(StoredFile $file, ?int $resourceId): void
    {
        $file->setResourceId($resourceId);
        $file->setUpdatedAt(new DateTime());
        $this->em->persist($file);
        $this->em->flush();
    }

    public function deleteFile(StoredFile $file): bool
    {
        $storagePath = $file->getStoragePath();
        $this->storage->removeFile($storagePath);
        $this->em->remove($file);
        $this->em->flush();
        return true;
    }
}
