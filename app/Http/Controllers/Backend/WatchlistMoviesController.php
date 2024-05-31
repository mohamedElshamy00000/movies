<?php

namespace App\Http\Controllers\Backend;

use App\Models\movie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesImdbDetails;
class WatchlistMoviesController extends Controller
{
    use HandlesImdbDetails;
    // add movie to watch list
    function addToWatchList($movie_id) {
        $user = Auth::user();
        $movie = movie::where('id', $movie_id)->first();
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $user->movies->syncWithoutDetaching([$movie_id => ['watchlist' => 1]]);
        return response()->json(['message' => 'Movie added to watchlist']);
    }

    // add movie to Favorite
    function addToFavorite($movie_id) {
        $user = Auth::user();
        $movie = movie::where('id', $movie_id)->first();
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }

        $user->movies->syncWithoutDetaching([$movie_id => ['favorite' => 1]]);
        
        // get more details about movie from (IMDB API)
        $this->fetchAndSaveIMDB($movie);

        return response()->json(['message' => 'Movie added to favorite']);
    }
}
