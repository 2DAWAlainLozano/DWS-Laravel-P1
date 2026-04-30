<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RabbitMQService;

class PublishPullRequestOpened extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-pr-opened';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a Pull Request Opened event to RabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(RabbitMQService $rabbitMQService)
    {
        $payload = [
            "event" => "pull_request.opened",
            "repository" => "plataforma-juegos",
            "branch" => "feature/reverb-chat",
            "author" => "developer2"
        ];

        $this->info('Publishing PR Opened event to RabbitMQ...');
        
        try {
            $rabbitMQService->publish('github_events', $payload);
            $this->info('Event successfully published to "github_events" queue.');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            $this->error('Failed to publish event: ' . $e->getMessage());
        }
    }
}
