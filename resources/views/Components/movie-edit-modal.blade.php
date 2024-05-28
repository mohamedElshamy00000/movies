
<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditModalLabel">Edit Movie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="movie-title" class="col-form-label">title:</label>
                        <input type="text" class="form-control" name="title" id="movie-title">
                    </div>
                    <div class="mb-3">
                        <label for="movie-director" class="col-form-label">director:</label>
                        <input type="text" class="form-control" name="director" id="movie-director">
                    </div>
                    <div class="mb-3">
                        <label for="movie-year" class="col-form-label">year:</label>
                        <input type="text" class="form-control" name="year" id="movie-year">
                    </div>
                    <div class="mb-3">
                        <label for="movie-country" class="col-form-label">country:</label>
                        <input type="text" class="form-control" name="country" id="movie-country">
                    </div>
                    <div class="mb-3">
                        <label for="movie-length" class="col-form-label">length:</label>
                        <input type="text" class="form-control" name="length" id="movie-length">
                    </div>
                    <div class="mb-3">
                        <label for="movie-genre" class="col-form-label">genre:</label>
                        <input type="text" class="form-control" name="genre" id="movie-genre">
                    </div>
                    <div class="mb-3">
                        <label for="movie-colour" class="col-form-label">colour:</label>
                        <input type="text" class="form-control" name="colour" id="movie-colour">
                    </div>
                    <input type="hidden" class="form-control" name="id" id="movie-id">

                    <button type='submit' class="btn btn-primary mt-3">Save</button>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var movieDetailsUrl = "{{ route('dashboard.movie.details', '') }}";
    var formSubmit = "{{ route('dashboard.movie.update', '') }}";
</script>

