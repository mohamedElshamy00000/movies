<?php

namespace App\Http\Controllers\Api;

use App\Models\movie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MovieController extends Controller
{
    
    function index(Request $request) {
        $queryMovies = movie::query();
        try {

            // if search by genre
            if ($request->has('genre')) {
                $genre = $request->query('genre');
                $queryMovies = $queryMovies->whereHas('genres', function ($query) use ($genre) {
                    $query->where('genre', $genre);
                });
            }

            // cash
            $cacheKey = 'movies_' . md5(serialize($request->query()));
            $movies = cache()->remember($cacheKey, 120, function() use ($queryMovies){
                return $queryMovies->paginate(10);
            });

            return response()->json($movies);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'server error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
        
    }
    
    public function show($id)
    {
        try {
            $movie = movie::with('genres')->findOrFail($id);
            return $movie;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Movie not found',
                'error_code' => 'MOVIE_NOT_FOUND'
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'server error',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
         
    }
}
