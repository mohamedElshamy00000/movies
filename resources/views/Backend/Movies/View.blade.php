@extends('layouts.app')

@section('content')
<main class="container">

    <div class="p-4 p-md-5 mb-4 text-white rounded position-relative" style="background-image: url('https://image.tmdb.org/t/p/original/{{ $movie->backdrop_path }}');background-size: cover;background-position: center;min-height: 380px;">
        <div class="overlay"></div>
        <div class="col-md-6 px-0" style="position: relative;z-index: 9;">
            <h1 class="display-4">{{ $movie->title }}</h1>
            <p class="lead my-3">{{ $movie->tagline }}</p>
            <div class="d-flex justify-content-start">
                <button
                    type="button" class="btn btn-info btn-lg px-4 me-sm-3 fw-bold add-to-watchlist {{ $inWatchList == true ? 'btn-dark' : 'btn-info' }}"
                    {{ $inWatchList == true ? 'disabled' : '' }}
                    data-movie-id="{{ $movie->id }}">
                    {{ $inWatchList == true ? 'Added to watch list' : 'Add to watch list' }}
                </button>
                <button
                    type="button" class="btn btn-lg px-4 add-to-favorite {{ $inFavoriteList == true ? 'btn-warning' : 'btn-light' }} "
                    {{ $inFavoriteList == true ? 'disabled' : '' }}
                    data-movie-id="{{ $movie->id }}">
                    <i class="bi bi-star"></i>
                </button>
            </div>
        </div>
        
    </div>
    
    <div class="row g-5">
        <div class="col-md-8">
  
            <article class="blog-post">
                <h2 class="blog-post-title">{{ $movie->title }}</h2>
                <p class="blog-post-meta">{{ $movie->year }} <a href="#">{{ $movie->director }}</a></p>
                <p>{{ $movie->overview }}</p>
            </article>
    
            <article class="blog-post">
            <h2 class="blog-post-title">More</h2>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>country</td>
                            <td>{{ $movie->country }}</td>
                        </tr>
                        <tr>
                            <td>popularity</td>
                            <td>{{ $movie->popularity }}</td>
                        </tr>
                        <tr>
                            <td>vote average</td>
                            <td>{{ $movie->vote_average }}</td>
                        </tr>
                        <tr>
                            <td>status</td>
                            <td>{{ $movie->status }}</td>
                        </tr>
                        <tr>
                            <td>genre</td>
                            <td>
                                @foreach ($movie->genres as $genre)
                                    <ul>
                                        <li>{{ $genre->genre }}</li>
                                    </ul>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>colour</td>
                            <td>{{ $movie->colour }}</td>
                        </tr>
                        <tr>
                            <td>length</td>
                            <td>{{ date('H:i', mktime(0,$movie->length)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </article>

        </div>

        <div class="col-md-4">
            <div class="position-sticky" style="top: 2rem;">
    
            <div class="p-4">
                <h4 class="">Movies</h4>
                <ol class="list-unstyled mb-0">
                    @foreach ($tenMovies as $movie)
                        <li><a href="{{ route('dashboard.showMovie', $movie->id) }}">{{ $movie->title }}</a></li>
                    @endforeach
                    <li><a href="{{ route('dashboard.home') }}">SEE ALL</a></li>

                </ol>
            </div>

            </div>
        </div>
    </div>
  
  </main>
@endsection


@section('script')
    <script>
        function showAlert(message) {
            var alertWindow = document.querySelector('#alertWindow');
            var messageElement = alertWindow.querySelector('.alert-message'); // Assuming a .alert-message element for content
        
            if (messageElement) {
            messageElement.textContent = message; // Update the message content

            $('#alertWindow').fadeIn();
            setTimeout(function() {
                $('#alertWindow').fadeOut();
            }, 4000);

            } else {
            console.error("Alert message element not found!");
            }
        }
    </script>
@endsection