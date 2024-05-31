<?php

namespace App\Http\Controllers\Backend;

use App\Models\movie;
use App\Traits\Cachabl;
use App\Models\MovieGenra;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesImdbDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    use HandlesImdbDetails;
    use Cachabl;

    // index fun in app/http/controllers/HomeController

    // get movies datatable (table)
    public function GetMovies(){
        // movies from cache
        $data = $this->getCachedData('all_movies', now()->addHours(10), function () {
            // If not found in cache, fetch movies from the database
            return movie::all();
        });
        // dd($data);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function($row){
                return $row->id;
            })
            ->addColumn('title', function($row){
                return strlen($row->title) > 10 ? substr($row->title, 0, 10) . '...' : $row->title;
            })

            ->addColumn('director', function($row){
                return $row->director;
            })
            ->addColumn('year', function($row){
                return $row->year;
            })
            ->addColumn('country', function($row){
                return strlen($row->country) > 10 ? substr($row->country, 0, 10) . '...' : $row->country;
            })
            ->addColumn('length', function($row){
                return date('H:i', mktime(0,$row->length));
            })
            ->addColumn('genre', function($row){
                $genres = '';
                foreach ($row->genres as $genre) {
                    $genres .=  $genre->genre . ', ';
                }
                return strlen($genres) > 10 ? substr($genres, 0, 10) . '...' : $genres;
            })
            ->addColumn('colour', function($row){
                return $row->colour;
            })
            ->addColumn('date', function($row){
                return $row->created_at;
            })
            // action butons
            ->addColumn('action', function($row){
                $btns = '<a href="'.route('dashboard.showMovie', $row->id).'" role="button" class="btn btn-outline-primary me-1">show</a>';
                $btns .= '<button data-bs-toggle="modal" data-bs-target="#EditModal" data-bs-id="'.$row->id.'" class="btn btn-outline-dark me-1">Edit</button>';
                $btns .= '<button type="button" onclick="deleteMovie('.$row->id.')" role="button" class="btn btn-outline-danger me-1 deleteMovieButton">delete</button>';
                return $btns;
            })
            ->rawColumns(['date','action'])
            ->make(true);
    }

    // get movie Details use in edit ajax request, ...
    function movieDetails($movie_id) {
        // Find the movie by ID
        $movie = movie::with('genres')->find($movie_id);
        // Check if movie is found

        if ($movie) {
            // movie data as an json
            return response()->json([
                'movie' => $movie,
                'genres' => $movie->genres
            ]);
        } else {
            return null;
        }
    }

    // destroy movie
    function destroyMovie($movie_id) {

        // Find the movie by ID
        $movie = movie::find($movie_id);
        
        if ($movie->delete()) {
            // Update the corresponding data in cache
            $this->updateCachedData('all_movies', Movie::all());
            // Movie deleted successfully
            return response()->json(['success' => true]);
        } else {
            // Deletion failed
            return response()->json(['error' => 'Failed to delete movie'], 500);
        }
    }
    
    // Edit movie
    function editMovie($movie_id) {
        // Find the movie by ID
        $movie = movie::find($movie_id);
        return view('Backend.Movies.Edit', compact('movie'));
    }
    // update movie data
    function updateMovie(Request $request) {
        // Find the movie by ID
        $movie = movie::find($request->id);
        // dd($movie->title);
        try {
            // Retrieve the validated input data
            $validated = $request->validate([
                'title'      => 'required|string|max:255',
                'director'   => 'required|string|max:255',
                'year'       => 'required|numeric',
                'country'    => 'required|string|max:255',
                'length'     => 'required|numeric',
                'genre'      => 'required|string|max:255',
                'colour'     => 'required|string|max:255',
            ]);


            $genres = explode(',', $request->genre);

            // Remove duplicate and trim genre names
            $uniqueGenres = array_unique(
                array_map(function ($genreName) {
                    $trimmedName = trim($genreName);
                    if ($trimmedName !== '') { // Check for non-empty value
                        return $trimmedName;
                    }
                }, $genres)
            );
            
            $existingGenreIds = $movie->genres()->pluck('genre')->toArray(); // Get existing genre IDs

            $genresToAttach = [];
            
            foreach ($uniqueGenres as $genreName) {
                if (!in_array($genreName, $existingGenreIds)) { // Check if genre already exists
                    $genresToAttach[] = [
                        'genre' => $genreName,
                        'movie_id' => $movie->id,
                    ];
                }
            }

            // Attach only new genres
            if (!empty($genresToAttach)) {
                $movie->genres()->createMany($genresToAttach);
            }

            // // Save genres with movie
            // foreach ($genresToAttach as $genreData) {
            //     $genre = new MovieGenra($genreData);
            //     $genre->save();
            // }


            // Update the corresponding data in cache
            $this->updateCachedData('all_movies', Movie::all());

            return redirect()->back()->with([
                'alert' => 'success',
                'message' => 'Movie updated successfully!'
            ]);
            
        } catch (\Throwable $th) {
            return back()->with([
                'alert'    => 'danger',
                'message' => 'Error ! ' . $th->getMessage(),
            ]);
        }
    }

    // add new movie
    function storeMovie(Request $request) {

        // Retrieve the validated input data
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'director'   => 'required|string|max:255',
            'year'       => 'required|numeric',
            'country'    => 'required|string|max:255',
            'length'     => 'required|numeric',
            'genre'      => 'required|string|max:255',
            'colour'     => 'required|string|max:255',
        ]);

        try {

            $movie = movie::create($validated);

            $genres = explode(',', $request->genre);

            $allGenres = [];
            foreach ($genres as $genreName) {
                $allGenres[] = ['genre' => trim($genreName)]; // Create array
            }
        
            // Save genres with movie
            foreach ($allGenres as $genreData) {
                $genre = new MovieGenra($genreData);
                $genre->movie_id = $movie->id;
                $genre->save();
            }

            // Update the corresponding data in cache
            $this->updateCachedData('all_movies', Movie::all());

            // success
            return redirect()->back()->with([
                'alert' => 'success',
                'message' => 'Movie Added successfully!'
            ]);

        } catch (\Throwable $th) {
            return back()->with([
                'alert'    => 'danger',
                'message' => 'Error !' . $th->getMessage(),
            ]);
        }
    }
    // show movie in single page
    function showMovie($movie_id) {
        $user = Auth::user();

        // get data from api and -> if data stored ? get : store in database
        $movie = movie::where('id', $movie_id)->first();

        // try {
            // if movie exist
            if ($movie) {
                // if data not inserted
                if($movie->tagline == null){
                    $movieData = $this->fetchAndSaveIMDB($movie);
                    if ($movieData) {
                        // Assign values from the API response to the model attributes
                        $movie->backdrop_path = $movieData['backdrop_path'];
                        $movie->overview = $movieData['overview'];
                        $movie->popularity = $movieData['popularity'];
                        $movie->poster_path = $movieData['poster_path'];
                        $movie->status = $movieData['status'];
                        $movie->tagline = $movieData['tagline'];
                        $movie->vote_average = $movieData['vote_average'];
        
                        // Save the movie to the database
                        $movie->save();
                    }
                    
                }
                // get 10 movies by rate
                $tenMovies = movie::orderBy('id', 'desc')->select('title','id')
                ->limit(10)
                ->get();

                // if movie is in favorite list
                $inFavoriteList = $user->movies()->wherePivot('favorite', true)->where('movies.id', $movie->id)->exists();
                // if movie is in watch list
                $inWatchList = $user->movies()->wherePivot('watchlist', true)->where('movies.id', $movie->id)->exists();

                // return to view page
                return view('Backend.Movies.View', compact('movie','tenMovies','inFavoriteList','inWatchList'));

            } else {
                // where not succeed
                return back()->with([
                    'alert'    => 'warning',
                    'message' => 'There was an issue',
                ]);
            }
        // } catch (\Throwable $th) {
        //     return back()->with([
        //         'alert'    => 'danger',
        //         'message' => 'Error !' . $th->getMessage(),
        //     ]);
        // }
    }
}
