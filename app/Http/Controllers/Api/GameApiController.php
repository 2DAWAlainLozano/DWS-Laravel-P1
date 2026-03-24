<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    public function published(): JsonResponse
    {
        $games = Game::published()
            ->select(['id', 'title', 'description', 'path'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $games,
        ]);
    }

    public function sessionStart(Request $request, Game $game): JsonResponse
    {
        $this->ensureGameAccess($request, $game);

        $validated = $request->validate([
            'started_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ]);

        return response()->json([
            'message' => 'Session started',
            'game_id' => $game->id,
            'user_id' => $request->user()->id,
            'started_at' => $validated['started_at'] ?? now()->toIso8601String(),
            'metadata' => $validated['metadata'] ?? null,
        ]);
    }

    public function event(Request $request, Game $game): JsonResponse
    {
        $this->ensureGameAccess($request, $game);

        $validated = $request->validate([
            'event' => ['required', 'string', 'max:100'],
            'score' => ['nullable', 'numeric'],
            'payload' => ['nullable', 'array'],
        ]);

        return response()->json([
            'message' => 'Event received',
            'game_id' => $game->id,
            'user_id' => $request->user()->id,
            'event' => $validated['event'],
            'score' => $validated['score'] ?? null,
            'payload' => $validated['payload'] ?? null,
            'received_at' => now()->toIso8601String(),
        ]);
    }

    private function ensureGameAccess(Request $request, Game $game): void
    {
        if ($game->is_published) {
            return;
        }

        if ($request->user()->isAdmin() || $request->user()->id === $game->user_id) {
            return;
        }

        abort(403, 'No puedes enviar eventos para este juego.');
    }
}
