<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PlayerStoreRequest;
use App\Http\Requests\PlayerUpdateRequest;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Player::class);

        $search = $request->get('search', '');

        $players = Player::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.players.index', compact('players', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Player::class);

        return view('app.players.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Player::class);

        $validated = $request->validated();

        $player = Player::create($validated);

        return redirect()
            ->route('players.edit', $player)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Player $player): View
    {
        $this->authorize('view', $player);

        return view('app.players.show', compact('player'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Player $player): View
    {
        $this->authorize('update', $player);

        return view('app.players.edit', compact('player'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        PlayerUpdateRequest $request,
        Player $player
    ): RedirectResponse {
        $this->authorize('update', $player);

        $validated = $request->validated();

        $player->update($validated);

        return redirect()
            ->route('players.edit', $player)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Player $player): RedirectResponse
    {
        $this->authorize('delete', $player);

        $player->delete();

        return redirect()
            ->route('players.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
