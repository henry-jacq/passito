<?php

declare(strict_types=1);

namespace App\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use App\Services\WebSocketService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class WebSocketServerCommand extends Command
{
    protected static $defaultName = 'app:start-websocket-server';
    protected static $defaultDescription = 'Starts the WebSocket server.';

    public function __construct()
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Port number to run the WebSocket server on', 8080);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Suppress deprecated warnings
        // This is a workaround for deprecated warnings in the Ratchet library
        // and should be removed when the library is updated to a version that
        // does not trigger these warnings.
        // Note: This is not a recommended practice for production code.
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

    
        $io = new SymfonyStyle($input, $output);
        $port = (int) $input->getOption('port');

        $io->title('Passito WebSocket Server');

        $io->success(sprintf('WebSocket server will listen on ws://0.0.0.0:%d', $port));

        try {

            if ($this->isPortInUse($port)) {
                $io->error(sprintf('Port %d is already in use. Please choose a different port.', $port));
                return Command::FAILURE;
            }
            
            $webSocket = new WebSocketService();

            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        $webSocket
                    )
                ),
                $port,
                '0.0.0.0'
            );

            $server->run();
        } catch (\Exception $e) {
            $io->error('WebSocket server crashed: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }

    private function isPortInUse(int $port): bool
    {
        $connection = @fsockopen('127.0.0.1', $port);

        if (is_resource($connection)) {
            fclose($connection);
            return true; // Port is already in use
        }

        return false; // Port is free
    }
}
