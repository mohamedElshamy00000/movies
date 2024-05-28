<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use App\Imports\MovieImport;
use App\Models\movie;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class MovieController extends Controller
{
    public function importMovie(ImportRequest $request){

        try {
            // Retrieve the validated input data
            $validated = $request->validated();

            // start importing
            $moviesImported = Excel::import(new MovieImport, $request->file('movies'));

            // Handle successful import
            if ($moviesImported) {
                return redirect()->route('dashboard.home')->with([
                    'alert'    => 'success',
                    'message' => 'Movies imported successfully',
                ]);
            } else {
                // where import might not succeed
                return back()->with([
                    'alert'    => 'warning',
                    'message' => 'There was an issue importing movies',
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                'alert'    => 'danger',
                'message' => 'Please try again later.',
            ]);
        }
        
    }

    // get movies datatable
    public function GetMovies(){

        $data = movie::get();
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
                return strlen($row->genre) > 10 ? substr($row->genre, 0, 10) . '...' : $row->genre;
            })
            ->addColumn('colour', function($row){
                return $row->colour;
            })
            ->addColumn('date', function($row){
                return $row->created_at;
            })
            // action butons
            ->addColumn('action', function($row){
                $btns = '<button data-bs-toggle="modal" data-bs-target="#EditModal" data-bs-id="'.$row->id.'" class="btn btn-outline-dark me-1">Edit</button>';
                $btns .= '<a href="'.route('dashboard.showMovie', $row->id).'" role="button" class="btn btn-outline-primary me-1">show</a>';
                $btns .= '<button type="button" onclick="deleteMovie('.$row->id.')" role="button" class="btn btn-outline-danger me-1 deleteMovieButton">delete</button>';
                return $btns;
            })
            ->rawColumns(['date','action'])
            ->make(true);
    }

    function movieDetails($movie_id) {
        // Find the movie by ID
        $movie = movie::where('id', $movie_id)->first();
        // Check if movie is found

        if ($movie) {
            // movie data as an json
            return response()->json([
                'movie' => $movie
            ]);
        } else {
            return null;
        }
    }

    function destroyMovie($movie_id) {

        // Find the movie by ID
        $movie = movie::where('id', $movie_id)->first();
        
        if ($movie->delete()) {
            // Movie deleted successfully
            return response()->json(['success' => true]);
        } else {
            // Deletion failed
            return response()->json(['error' => 'Failed to delete movie'], 500);
        }
    }

    function editMovie($movie_id) {
        // Find the movie by ID
        $movie = movie::where('id', $movie_id)->first();
        return view('Backend.Movies.Edit', compact('movie'));
    }
    function updateMovie(Request $request) {
        // Find the movie by ID
        $movie = movie::where('id', $request->id)->first();

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

            if ($movie->update($validated)) {
                // success
                return redirect()->back()->with([
                    'alert' => 'success',
                    'message' => 'Movie updated successfully!'
                ]);
            } else {
                // where not succeed
                return back()->with([
                    'alert'    => 'warning',
                    'message' => 'There was an issue',
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                'alert'    => 'danger',
                'message' => 'Error !' . $th->getMessage(),
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

            if (movie::create($validated)) {
                // success
                return redirect()->back()->with([
                    'alert' => 'success',
                    'message' => 'Movie Added successfully!'
                ]);
            } else {
                // where not succeed
                return back()->with([
                    'alert'    => 'warning',
                    'message' => 'There was an issue',
                ]);
            }
        } catch (\Throwable $th) {
            return back()->with([
                'alert'    => 'danger',
                'message' => 'Error !' . $th->getMessage(),
            ]);
        }
    }
    // show movie in single page
    function showMovie() {
        return 'hi';
    }
}
