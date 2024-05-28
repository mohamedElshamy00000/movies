

var EditModalLabel = document.getElementById('EditModal')

// EditModalLabel.addEventListener('show', function (event) {
$(document).on('shown.bs.modal', EditModalLabel,function (event) {

    // Button that triggered the modal
    var button = event.relatedTarget
    var movieId = button.getAttribute('data-bs-id') // data-bs-id attributes
    var modalTitle = EditModalLabel.querySelector('.modal-title')

    modalTitle.textContent = 'Edit movie #' + movieId
    var url = movieDetailsUrl + "/" + movieId; // movieDetailsUrl in blade modal file
    
    //AJAX request
    $.ajax({
        url: url,
        method: "GET",
        beforeSend: function() {
            // Disable input fields while data is being fetched
            EditModalLabel.querySelectorAll('input').forEach(function(input) {
              input.disabled = true;
            });
        },
        success: function(data) {

            // Update the modal's content.

            var MovieTitle    = EditModalLabel.querySelector('#movie-title').value = data.movie.title
            var MovieDirector = EditModalLabel.querySelector('#movie-director').value = data.movie.director
            var MovieYear     = EditModalLabel.querySelector('#movie-year').value = data.movie.year
            var MovieCountry  = EditModalLabel.querySelector('#movie-country').value = data.movie.country
            var MovieLength   = EditModalLabel.querySelector('#movie-length').value = data.movie.length
            var MovieGenre    = EditModalLabel.querySelector('#movie-genre').value = data.movie.genre
            var MovieColour   = EditModalLabel.querySelector('#movie-colour').value = data.movie.colour
            var Movieid       = EditModalLabel.querySelector('#movie-id').value = movieId

            // Re-enable input fields after successful data import
            EditModalLabel.querySelectorAll('input').forEach(function(input) {
                input.disabled = false;
            });

            $('#editForm').attr('action', formSubmit + '/' + movieId);
        },
        error: function(error) {
            $(button).removeClass('loading');
            showAlert('Error!');
            // Optionally re-enable inputs on error (consider error handling strategy)
            EditModalLabel.querySelectorAll('input').forEach(function(input) {
                input.disabled = false; // Re-enable inputs on error (optional)
            });
        }
    });
})
