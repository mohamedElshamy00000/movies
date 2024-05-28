@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- incloud error messages component --}}
            @include('Backend.flash.messages')

            <div class="btn-toolbar justify-content-between align-items-center my-3">
                <h4><i class="fa-solid fa-film me-2"></i>Movies</h4>
            </div>

            <div class="card">
                <div class="card-header">

                    <div class="btn-toolbar justify-content-between align-items-center">
                        {{-- import form --}}
                        <form action="{{ route('dashboard.excel.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <button type="submit" role="button" class="btn btn-dark">Import From excel</button>
                                <input type="file" name="movies" class="form-control" aria-describedby="btnGroupAddon">
                            </div>
                        </form>

                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#AddModal">Add New</button>

                    </div>
                </div>

                <div class="card-body">
                    <table class="table yajra-datatable" id="moviesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>title</th>
                                <th>director</th>
                                <th>year</th>
                                <th>country</th>
                                <th>length</th>
                                <th>genre</th>
                                <th>colour</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- alert --}}
@include('Components.dark-alert')

{{-- modals --}}
@include('Components.movie-edit-modal')
@include('Components.movie-add-modal')

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

    // Send DELETE request to remove the movie
    function deleteMovie(movieId) {
        var button = event.srcElement;
        console.log(button);
        // disabled button and add a loading class
        $(button).addClass('loading').prop('disabled', true);

        var deleteUrl = "{{route('dashboard.movie.destroy', '')}}"+"/"+movieId;
        $.ajax({
            url: deleteUrl,
            method: "GET",
            success: function(data) {
                $(button).removeClass('loading').prop('disabled', false);
                showAlert('Deleted Succesfuly!');
                // Remove the movie row from the DataTables table
                var table = $('#moviesTable').DataTable();
                    table.row('#' + movieId).remove().draw();
            },
            error: function(error) {
                $(button).removeClass('loading');
                showAlert('Error!');
            }
        });
    }
    
    $(document).ready( function () {

        // Fetch movie details using AJAX
        var table = $('#moviesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering : true,
            order : [[ 1, "desc" ]] ,
            ajax: "{{ route('dashboard.getMovies') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'director', name: 'director'},
                {data: 'year', name: 'year'},
                {data: 'country', name: 'country'},
                {data: 'length', name: 'length'},
                {data: 'genre', name: 'genre'},
                {data: 'colour', name: 'colour'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true,
                },
            ],
            dom: 'Bfrtip',
            buttons:[],
            
        });

    });

</script>
@endsection