<?php

namespace App\Http\Controllers\Api;

use App\Models\movie;
use Illuminate\Http\Request;
use App\Traits\HandlesImdbDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;

class WatchlistMoviesController extends Controller
{
    use HandlesImdbDetails;

    // add movie to watch list
    function addToWatchList($movie_id) {

        $user = Auth::user();
        
        try {
            $movie = movie::where('id',$movie_id)->first();
            if (!$movie) {
                return response()->json([
                    'message' => 'Movie not found',
                    'error_code' => 'MOVIE_NOT_FOUND'
                ], 500);
            }
            
            $user->movies()->syncWithoutDetaching([$movie_id => ['watchlist' => 1]]);
            return response()->json(['message' => 'Movie added to watchlist']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'server error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }

    }

    // add movie to Favorite
    function addToFavorite($movie_id) {
        try{
            $user = Auth::user();
            $movie = movie::findOrFail($movie_id);
            
            if (!$movie) {
                return response()->json(['message' => 'Movie not found'], 404);
            }

            $user->movies()->syncWithoutDetaching([$movie_id => ['favorite' => true]]);
            
            // get more details about movie from (IMDB API) and save it
            $this->fetchAndSaveIMDB($movie);
            return response()->json(['message' => 'Movie added to Favorite']);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'server error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
}
