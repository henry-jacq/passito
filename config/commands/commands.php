<?php

declare(strict_types=1);

return [
    \App\Command\CreateSuperAdminCommand::class,
    \App\Command\CleanupExpiredFilesCommand::class,
    \App\Command\DatabaseSeederCommand::class,
    \App\Command\WebSocketServerCommand::class,
    \App\Command\MaintenanceModeCommand::class,
    \App\Command\JobWorkerCommand::class,
    \App\Command\JobSupervisorCommand::class,
    \App\Command\JobHealthCheckCommand::class,
];
