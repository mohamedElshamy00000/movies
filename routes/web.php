<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('dashboard')->middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard.home');
    Route::get('/getMovies', [App\Http\Controllers\backend\MovieController::class, 'getMovies'])->name('dashboard.getMovies');
    Route::get('/showMovie/{movie}', [App\Http\Controllers\backend\MovieController::class, 'showMovie'])->name('dashboard.showMovie');
    
    // import from file
    Route::post('/movies/import', [App\Http\Controllers\backend\ImportMoviesController::class, 'importMovie'])->name('dashboard.excel.import');

    // details
    Route::get('/movies/details/{movie}', [App\Http\Controllers\backend\MovieController::class, 'movieDetails'])->name('dashboard.movie.details');

    // CRUD - edit
    Route::get('/movies/edit/{movie}', [App\Http\Controllers\backend\MovieController::class, 'editMovie'])->name('dashboard.movie.edit');
    Route::post('/movies/update/{movie}', [App\Http\Controllers\backend\MovieController::class, 'updateMovie'])->name('dashboard.movie.update');

    // CRUD - delete
    Route::get('/movies/destroy/{movie}', [App\Http\Controllers\backend\MovieController::class, 'destroyMovie'])->name('dashboard.movie.destroy');

    // CRUD - add new
    Route::post('/movies/store', [App\Http\Controllers\backend\MovieController::class, 'storeMovie'])->name('dashboard.movie.store');


});
