<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Interfaces\JobInterface;
use App\Services\OutpassService;

class CleanupExpiredFiles implements JobInterface
{
    public function __construct(
        private readonly OutpassService $outpassService
    ) {}

    public function handle(array $payload): ?array
    {
        // Remove documents and attachments for expired outpasses
        $this->outpassService->removeExpireOutpassFiles();

        return [
            'status' => 'completed',
            'timestamp' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ];
    }
}
