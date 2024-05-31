<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\WatchlistMoviesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:api')->group(function(){
    Route::get('movies', [MovieController::class, 'index']);
    Route::get('movies/{id}', [MovieController::class, 'show']);
    Route::post('/movies/watchlist/{id}', [WatchlistMoviesController::class, 'addToWatchList']);
    Route::post('/movies/favorite/{id}', [WatchlistMoviesController::class, 'addToFavorite']);
});