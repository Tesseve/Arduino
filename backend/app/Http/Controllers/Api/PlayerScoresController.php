<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScoreResource;
use App\Http\Resources\ScoreCollection;

class PlayerScoresController extends Controller
{
    public function index(Request $request, Player $player): ScoreCollection
    {
        $this->authorize('view', $player);

        $search = $request->get('search', '');

        $scores = $player
            ->scores()
            ->search($search)
            ->latest()
            ->paginate();

        return new ScoreCollection($scores);
    }

    public function store(Request $request, Player $player): ScoreResource
    {
        $this->authorize('create', Score::class);

        $validated = $request->validate([
            'value' => ['required', 'max:255'],
            'mode' => ['required', 'max:255', 'string'],
        ]);

        $score = $player->scores()->create($validated);

        return new ScoreResource($score);
    }
}
