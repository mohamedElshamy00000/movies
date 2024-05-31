<?php

namespace App\Models;

use App\Models\MovieGenra;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class movie extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'title',
        'director',
        'year',
        'country',
        'length',
        'colour',
        'backdrop_path',
        'overview',
        'popularity',
        'poster_path',
        'status',
        'tagline',
        'vote_average',
    ];


    function users(){
        return $this->belongsToMany(movie::class, 'user_movies')->withPivot('watchlist', 'favorite')
        ->withTimestamps();
    }
    public function genres()
    {
        return $this->hasMany(MovieGenra::class, 'movie_id');
    }
    
}
