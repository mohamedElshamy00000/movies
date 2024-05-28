@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            {{-- incloud error messages component --}}
            @include('Backend.flash.messages')
            @include('Components.dark-alert')

            <div class="card">
                <div class="card-header">

                    <div class="btn-toolbar justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fa-solid fa-film me-2"></i>Edit Movie</h4>

                        <a href="{{ url()->previous() }}" class="btn btn-dark"> <i class="fa fa-arrow-left"></i> </a>

                    </div>
                </div>

                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
