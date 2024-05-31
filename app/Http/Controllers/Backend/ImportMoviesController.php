<?php

namespace App\Http\Controllers\Backend;

use App\Imports\MovieImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportRequest;
use Maatwebsite\Excel\Facades\Excel;

class ImportMoviesController extends Controller
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

}
