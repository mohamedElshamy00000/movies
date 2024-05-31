<?php

namespace Tests\Feature;

use App\Models\movie;
use App\Models\User;
use Tests\TestCase;

class MovieTest extends TestCase
{

    /**
     * feature test get movies.
    */
    function test_can_get_movies() {

        // disable middleware
        $this->withoutMiddleware();
        // call api
        $response = $this->get('/api/movies');
        $response->assertStatus(200);
    }
    
    /**
     * feature test show movie details
    */
    function test_can_show_movie() {

        // disable middleware
        $this->withoutMiddleware();

        // create movie
        $movie = movie::first();

        // call api
        $response = $this->get('/api/movies/', ['movie_id' => $movie->id]);

        $response->assertStatus(200);
    }

    /**
     * feature test can add to watch list
    */
    function test_can_add_movie_to_watchlist() {

        // disable middleware
        $this->withoutMiddleware();

        // get movie
        $movie = movie::first();
        // get user
        $user = User::first();

        // call api
        $response = $this->actingAs($user)->post('/api/movies/watchlist/' . $movie->id, []);
        $response->assertStatus(200);
    }

    /**
     * feature test can add to favorite list
    */
    function test_can_add_movie_to_favorite() {

        // disable middleware
        $this->withoutMiddleware();

        // get movie
        $movie = movie::first();
 
        // get user
        $user = User::first();

        // call api
        $response = $this->actingAs($user)->post('/api/movies/favorite/' . $movie->id, []);
        $response->assertStatus(200);
    }
    
}
