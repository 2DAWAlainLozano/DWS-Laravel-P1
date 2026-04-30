<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RabbitMQService;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhooks.
     */
    public function handle(Request $request, RabbitMQService $rabbitMQService)
    {
        // GitHub sends the event type in the X-GitHub-Event header
        $event = $request->header('X-GitHub-Event');
        $payload = $request->all();

        Log::info('GitHub Webhook received', ['event' => $event]);

        // We specifically look for pull_request events
        if ($event === 'pull_request') {
            $action = $payload['action'] ?? '';
            
            if ($action === 'opened') {
                $rabbitmqPayload = [
                    "event" => "pull_request.opened",
                    "repository" => $payload['repository']['name'] ?? 'unknown',
                    "branch" => $payload['pull_request']['head']['ref'] ?? 'unknown',
                    "author" => $payload['pull_request']['user']['login'] ?? 'unknown'
                ];

                try {
                    $rabbitMQService->publish('github_events', $rabbitmqPayload);
                    return response()->json(['status' => 'event_published_to_rabbitmq'], 200);
                } catch (\Exception $e) {
                    Log::error('Failed to publish GitHub event to RabbitMQ', ['error' => $e->getMessage()]);
                    return response()->json(['status' => 'error', 'message' => 'failed_to_publish'], 500);
                }
            }
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}
