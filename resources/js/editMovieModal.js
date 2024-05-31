

var EditModalLabel = document.getElementById('EditModal')

function modalFields(data) {
    const movieFields = ['title', 'director', 'year', 'country', 'length', 'genre', 'colour','id'];
    for (const field of movieFields) {
        if (field === 'genre') {
            // Handle genres 

            if (Array.isArray(data.genres)) {
              const genreNames = data.genres.map(genre => genre.genre).join(', ');
              EditModalLabel.querySelector(`#movie-${field}`).value = genreNames;
            } else if (typeof data.genres === 'string') {
                EditModalLabel.querySelector(`#movie-${field}`).value = data.genres;
            }
        } else {
            EditModalLabel.querySelector(`#movie-${field}`).value = data.movie[field];
        }
    }
}

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
            modalFields(data);

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
