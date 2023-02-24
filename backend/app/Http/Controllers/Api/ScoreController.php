<?php

namespace App\Http\Controllers\Api;

use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScoreResource;
use App\Http\Resources\ScoreCollection;
use App\Http\Requests\ScoreStoreRequest;
use App\Http\Requests\ScoreUpdateRequest;

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

        $validated = $request->validated();

        $score = Score::create($validated);

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
}
