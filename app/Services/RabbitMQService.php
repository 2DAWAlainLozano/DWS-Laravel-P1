<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.host', 'localhost'),
            config('queue.connections.rabbitmq.port', 5672),
            config('queue.connections.rabbitmq.user', 'guest'),
            config('queue.connections.rabbitmq.password', 'guest')
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * Publish an event to a specific queue.
     */
    public function publish(string $queue, array $payload)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(
            json_encode($payload),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $this->channel->basic_publish($msg, '', $queue);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
