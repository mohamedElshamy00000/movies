<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait HandlesImdbDetails
{
    /*
    * Retrieve movie details from IMDB
    * movie : used to retrieve data for this movie
    */
    function fetchAndSaveIMDB($movie) {

        try{

            // My IMDB api key
            $imdbApiKey = config('services.imdb.api_key');
            
            $response   = Http::get("https://api.themoviedb.org/3/movie/{$movie->id}?api_key={$imdbApiKey}");

            if($response->successful()){
                $details = $response->json();
                return $details;
            } else {
                // API errors
                return null;
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'server error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
}
