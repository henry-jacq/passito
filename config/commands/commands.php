<?php

declare(strict_types=1);

return [
    \App\Command\CreateSuperAdminCommand::class,
    \App\Command\ProcessEmailQueueCommand::class,
    \App\Command\RemoveExpiredOutpassCommand::class,
    \App\Command\DatabaseSeederCommand::class,
    \App\Command\WebSocketServerCommand::class,
    \App\Command\MaintenanceModeCommand::class,
];
