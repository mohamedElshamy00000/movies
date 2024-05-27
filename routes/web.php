<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::prefix('dashboard')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard.home');
    Route::get('/getMovies', [App\Http\Controllers\backend\MovieController::class, 'getMovies'])->name('dashboard.getMovies');

    Route::post('/import', [App\Http\Controllers\backend\MovieController::class, 'importMovie'])->name('dashboard.excel.import');

});
