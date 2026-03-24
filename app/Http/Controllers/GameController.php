<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Game::class, 'game');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $games = Game::query()
            ->when(
                $user->isManager(),
                fn ($query) => $query->where('user_id', $user->id)
            )
            ->with('user:id,name')
            ->latest()
            ->get();

        return Inertia::render('Games/Index', [
            'games' => $games,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Games/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        Game::create($validated);

        return redirect()
            ->route('manage.games.index')
            ->with('success', 'Juego creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game): Response
    {
        return Inertia::render('Games/Play', [
            'game' => $game,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game): Response
    {
        return Inertia::render('Games/Edit', [
            'game' => $game,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game): RedirectResponse
    {
        $game->update($request->validated());

        return redirect()
            ->route('manage.games.index')
            ->with('success', 'Juego actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()
            ->route('manage.games.index')
            ->with('success', 'Juego eliminado correctamente.');
    }

    public function togglePublish(Game $game): RedirectResponse
    {
        $this->authorize('publish', $game);

        $game->update([
            'is_published' => ! $game->is_published,
        ]);

        return redirect()
            ->route('manage.games.index')
            ->with('success', 'Estado de publicación actualizado.');
    }

    public function catalog(): Response
    {
        $games = Game::published()
            ->select(['id', 'title', 'description', 'path'])
            ->latest()
            ->get();

        return Inertia::render('Games/Catalog', [
            'games' => $games,
        ]);
    }

    public function play(Request $request, Game $game): Response
    {
        $canPreviewUnpublished = $request->user()->isAdmin() || $game->user_id === $request->user()->id;

        if (! $game->is_published && ! $canPreviewUnpublished) {
            abort(403, 'El juego no esta publicado.');
        }

        return Inertia::render('Games/Play', [
            'game' => $game,
        ]);
    }
}
