<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\PlayerScoresController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API',
    ]);
});

Route::name('api.')
    ->group(function () {
        Route::apiResource('players', PlayerController::class);

        // Player Scores
        Route::get('/players/{player}/scores', [
            PlayerScoresController::class,
            'index',
        ])->name('players.scores.index');
        Route::post('/players/{player}/scores', [
            PlayerScoresController::class,
            'store',
        ])->name('players.scores.store');

        Route::apiResource('scores', ScoreController::class);
    });
