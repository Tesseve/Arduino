<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScoreResource;
use App\Http\Resources\ScoreCollection;
use App\Http\Requests\ScoreStoreRequest;
use App\Http\Requests\ScoreUpdateRequest;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    public function index(Request $request): ScoreCollection
    {
        $this->authorize('view-any', Score::class);

        $search = $request->get('search', '');

        $scores = Score::search($search)
            ->latest()
            ->paginate();

        return new ScoreCollection($scores);
    }

    public function store(ScoreStoreRequest $request): ScoreResource
    {
       $this->authorize('create', Score::class);

        $player = Player::firstOrCreate([
            "name" => $request->name,
        ]);

        $score = $player->scores()->create([
            'value' => $request->value,
            'mode' => $request->mode,
        ]);

        return new ScoreResource($score);
    }

    public function show(Request $request, Score $score): ScoreResource
    {
        $this->authorize('view', $score);

        return new ScoreResource($score);
    }

    public function update(
        ScoreUpdateRequest $request,
        Score $score
    ): ScoreResource {
        $this->authorize('update', $score);

        $validated = $request->validated();

        $score->update($validated);

        return new ScoreResource($score);
    }

    public function destroy(Request $request, Score $score): Response
    {
        $this->authorize('delete', $score);

        $score->delete();

        return response()->noContent();
    }

    public function top(Request $request): ScoreCollection
    {
        $this->authorize('view-any', Score::class);

        $search = $request->get('search', '');

        $scores = Score::select('scores.*')
            ->whereIn('scores.id', function($query) {
                $query->select('s.id')
                    ->from('scores as s')
                    ->leftJoin('scores as s2', function($join) {
                        $join->on('s.mode', '=', 's2.mode')
                            ->on('s.value', '>=', 's2.value');
                    })
                    ->groupBy('s.id')
                    ->havingRaw('COUNT(*) <= 10')
                    ->orderBy('s.mode', 'asc')
                    ->orderBy('s.value', 'asc');
            })
            ->orderBy('mode', 'asc')
            ->orderBy('value', 'asc')
            ->get();

        return new ScoreCollection($scores->groupBy('mode'));
    }
}
