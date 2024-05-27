<?php

namespace App\Imports;

use App\Models\movie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Validators\Failure;
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
        $existingMovie = Movie::where('title', $row['title'])
        ->where('year', $row['year'])
        ->first();

        // Skip adding the duplicate
        if ($existingMovie) {
            return null;
        }
        return new movie([
            'title'    => trim($row['title']),
            'director' => trim($row['director']),
            'year'     => trim($row['year']),
            'country'  => trim($row['country']),
            'length'   => trim($row['length']),
            'genre'    => trim($row['genre']),
            'colour'   => trim($row['colour']),
        ]);
    
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
