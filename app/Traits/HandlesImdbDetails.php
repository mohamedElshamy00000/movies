<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Exception;

trait HandlesImdbDetails
{
    /*
    * Retrieve movie details from IMDB
    * movie : used to retrieve data for this movie
    */
    function fetchAndSaveIMDB($movie) {
        // My IMDB api key
        $imdbApiKey = config('services.imdb.api_key');
        
        $response   = Http::get("https://api.themoviedb.org/3/movie/{$movie->id}?api_key={$imdbApiKey}");

        if($response->successful()){
            $details = $response->json();
            return $details;
        } else {
            // API errors
            throw new Exception("Error Fetching TMDB Data: " . $response->status());
        }
    }
}
