<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;

class PublishCommit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-commit {message} {author}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a Git commit event to RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(RabbitMQService $rabbitMQService)
    {
        $payload = [
            "event" => "git.commit",
            "repository" => "plataforma-juegos",
            "message" => $this->argument('message'),
            "author" => $this->argument('author'),
            "timestamp" => now()->toIso8601String()
        ];

        try {
            $rabbitMQService->publish('github_events', $payload);
            $this->info("Event published to RabbitMQ: Commit by " . $this->argument('author'));
        } catch (\Exception $e) {
            $this->error("Failed to publish commit: " . $e->getMessage());
        }
    }
}
