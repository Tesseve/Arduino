<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerCollection;
use App\Http\Requests\PlayerStoreRequest;
use App\Http\Requests\PlayerUpdateRequest;

class PlayerController extends Controller
{
    public function index(Request $request): PlayerCollection
    {
        $this->authorize('view-any', Player::class);

        $search = $request->get('search', '');

        $players = Player::search($search)
            ->latest()
            ->paginate();

        return new PlayerCollection($players);
    }

    public function store(PlayerStoreRequest $request): PlayerResource
    {
        $this->authorize('create', Player::class);

        $validated = $request->validated();

        $player = Player::create($validated);

        return new PlayerResource($player);
    }

    public function show(Request $request, Player $player): PlayerResource
    {
        $this->authorize('view', $player);

        return new PlayerResource($player);
    }

    public function update(
        PlayerUpdateRequest $request,
        Player $player
    ): PlayerResource {
        $this->authorize('update', $player);

        $validated = $request->validated();

        $player->update($validated);

        return new PlayerResource($player);
    }

    public function destroy(Request $request, Player $player): Response
    {
        $this->authorize('delete', $player);

        $player->delete();

        return response()->noContent();
    }
}
