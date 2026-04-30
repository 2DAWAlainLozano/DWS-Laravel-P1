<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:consume-rabbitmq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume events from RabbitMQ and trigger actions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            config('queue.connections.rabbitmq.host', 'localhost'),
            config('queue.connections.rabbitmq.port', 5672),
            config('queue.connections.rabbitmq.user', 'guest'),
            config('queue.connections.rabbitmq.password', 'guest')
        );
        $channel = $connection->channel();

        $channel->queue_declare('github_events', false, true, false, false);

        $this->info(' [*] Waiting for messages in "github_events". To exit press CTRL+C');

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $this->info(' [x] Received event: ' . ($data['event'] ?? 'unknown'));
            $this->line(json_encode($data, JSON_PRETTY_PRINT));

            // Hypothetical automatic review or notification logic
            if (($data['event'] ?? '') === 'pull_request.opened') {
                $this->warn('Triggering automatic review for branch: ' . ($data['branch'] ?? 'n/a'));
                // Add logic here: e.g., calling a validation service or sending a Slack message
            }

            $msg->ack();
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('github_events', '', false, false, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
