<?php

namespace App\Imports;

use App\Models\movie;
use App\Models\MovieGenra;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;

class MovieImport implements ToModel, WithUpserts, WithUpsertColumns, WithHeadingRow,WithValidation
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // if duplicates
        $existingMovie = Movie::where('title', $row['title'])->where('year', $row['year'])->first();

        // Skip adding the duplicate
        if ($existingMovie) {
            return null;
        }

        $movie = new movie([
            'title'    => trim($row['title']),
            'director' => trim($row['director']),
            'year'     => trim($row['year']),
            'country'  => trim($row['country']),
            'length'   => trim($row['length']),
            'colour'   => trim($row['colour']),
        ]);

        // Save movie
        $movie->save();

        // format genres
        $genres = explode('-', $row['genre']);

        // Remove duplicate and trim genre names
        $uniqueGenres = array_unique(
            array_map(function ($genreName) {
                $trimmedName = trim($genreName);
                if ($trimmedName !== '') { // Check for non-empty value
                    return $trimmedName;
                }
            }, $genres)
        );

        $formattedGenres = [];
        foreach ($uniqueGenres as $genreName) {
            $formattedGenres[] = ['genre' => trim($genreName)]; // Create associative array
        }
    
        // Save genres with movie
        foreach ($formattedGenres as $genreData) {
            $genre = new MovieGenra($genreData);
            $genre->movie_id = $movie->id;
            $genre->save();
        }

        return $movie;
    }

    function uniqueBy() {
        return 'title';
    }
    function upsertColumns() {
        return ['title'];
    }
    function headingRow() : int {
        return 1;
    }
    public function rules(): array
    {
        return [
            'title' => 'required',
            'director' => 'required',
            'year' => 'required',
            'country' => 'required',
            'length' => 'required',
            'genre' => 'required',
            'colour' => 'nullable',
        ];
    }

    public function failures()
    {
        // Handle import failures (optional)
        return Failure::catchError($this->failures());
    }
}
