@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome, {{ $user->first_name }} {{ $user->last_name}}</h4>
                    You are logged in!
                </div>


                <div class="card-body">
                    <h3>Add to your CV:</h3>
                    <a href="/jobs/create" class="btn btn-primary">Jobs</a>
                    <a href="/educations/create" class="btn btn-primary">Education</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
