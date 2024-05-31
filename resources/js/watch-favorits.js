// CSRF Token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Authorization': 'Bearer oFgQV7qYWNgTK3NNKAEfRD61O5ADglhXUhQ0uJKnkj2N1cN4UKZKBMkaX2Vr' // user auth key
    }
});

function handleAjaxError(response) {
    let message = 'An error occurred';
    if (response.responseJSON && response.responseJSON.message) {
        message = response.responseJSON.message;
    }
    showAlert('Error: ' + message);
}

// add to watchlist list
$('.add-to-watchlist').on('click', function() {
    var button = $(this);
    var movieId = button.data('movie-id');
    var movieId = button.data('movie-id');
    button.addClass('loading').prop('disabled', true);

    $.ajax({
        url: '/api/movies/watchlist/' + movieId,
        type: 'POST',
        success: function(response) {
            button.removeClass('btn-info').addClass('btn-dark');
            button.text('Added to watch list');
            button.removeClass('loading');
            showAlert(response.message);
        },
        error: function(response) {
            button.removeClass('loading').prop('disabled', false);
            handleAjaxError(response);
        }
    });
});

// add to favorite list
$('.add-to-favorite').on('click', function() {
    var button = $(this);
    button.addClass('loading').prop('disabled', true);
    var movieId = button.data('movie-id');
    var movieId = button.data('movie-id');
    $.ajax({
        url: '/api/movies/favorite/' + movieId,
        type: 'POST',
        success: function(response) {
            button.removeClass('btn-light').addClass('btn-warning');
            button.removeClass('loading');
            showAlert(response.message);
        },
        error: function(response) {
            button.removeClass('loading').prop('disabled', false);
            handleAjaxError(response);
        }
    });
});
    
