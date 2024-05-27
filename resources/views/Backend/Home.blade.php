@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            {{-- incloud error messages component --}}
            @include('Backend.flash.messages')

            <div class="card">
                <div class="card-header">

                    <div class="btn-toolbar justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa-solid fa-film me-2"></i>Movies</h4>

                        {{-- import form --}}
                        <form action="{{ route('dashboard.excel.import') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <button type="submit" role="button" class="btn btn-dark">Import From excel</button>
                                <input type="file" name="movies" class="form-control" aria-describedby="btnGroupAddon">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table yajra-datatable" id="movies">
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
@endsection


@section('script')
<script>
    $(document).ready( function () {

        var table = $('#movies').DataTable({
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
            dom: 'Bfrtip'
        });
    });

</script>
@endsection