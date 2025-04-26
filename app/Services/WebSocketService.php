<?php

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketService implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $ip = $conn->remoteAddress ?? 'unknown';
        $timestamp = date('Y-m-d H:i:s');

        $this->clients->attach($conn);

        echo "[{$timestamp}] New WebSocket connection from {$ip}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "New message from ({$from->resourceId}): $msg\n";

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send("Client {$from->resourceId} says: $msg");
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $ip = $conn->remoteAddress ?? 'unknown';
        $timestamp = date('Y-m-d H:i:s');

        $this->clients->detach($conn);

        echo "[{$timestamp}] Connection closed from {$ip}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $ip = $conn->remoteAddress ?? 'unknown';
        echo "[ERROR] ({$ip}): {$e->getMessage()}\n";
        $conn->close();
    }
}
