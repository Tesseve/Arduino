<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Player;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ScoreStoreRequest;
use App\Http\Requests\ScoreUpdateRequest;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Score::class);

        $search = $request->get('search', '');

        $scores = Score::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.scores.index', compact('scores', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Score::class);

        $players = Player::pluck('name', 'id');

        return view('app.scores.create', compact('players'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScoreStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Score::class);

        $validated = $request->validated();

        $score = Score::create($validated);

        return redirect()
            ->route('scores.edit', $score)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Score $score): View
    {
        $this->authorize('view', $score);

        return view('app.scores.show', compact('score'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Score $score): View
    {
        $this->authorize('update', $score);

        $players = Player::pluck('name', 'id');

        return view('app.scores.edit', compact('score', 'players'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ScoreUpdateRequest $request,
        Score $score
    ): RedirectResponse {
        $this->authorize('update', $score);

        $validated = $request->validated();

        $score->update($validated);

        return redirect()
            ->route('scores.edit', $score)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Score $score): RedirectResponse
    {
        $this->authorize('delete', $score);

        $score->delete();

        return redirect()
            ->route('scores.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
