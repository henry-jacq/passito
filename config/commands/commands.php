<?php

declare(strict_types=1);

return [
    \App\Command\CreateSuperAdminCommand::class,
    \App\Command\RemoveExpiredOutpassCommand::class,
    \App\Command\DatabaseSeederCommand::class,
    \App\Command\WebSocketServerCommand::class,
    \App\Command\MaintenanceModeCommand::class,
    \App\Command\JobWorkerCommand::class,
    // \App\Command\GenerateSitemapCommand::class,
    // \App\Command\GenerateRobotsTxtCommand::class,
    // \App\Command\GenerateApiDocumentationCommand::class,
    // \App\Command\GenerateDatabaseBackupCommand::class,
];
