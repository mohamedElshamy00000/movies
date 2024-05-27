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
                $btns = '<button type="submit" role="button" class="btn btn-outline-dark me-1">Edit</button>';
                $btns .= '<button type="submit" role="button" class="btn btn-outline-primary me-1">show</button>';
                $btns .= '<button type="submit" role="button" class="btn btn-outline-danger me-1">delete</button>';
                return $btns;
            })
            ->rawColumns(['date','action'])
            ->make(true);
    }

    
}
