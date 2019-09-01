@extends('layouts.app')

@section('content')
    <div class="spacer-20"></div>

    <div class="container">
        <h1 class="title">Add job to CV</h1>
        <form method="POST" action="/jobs" enctype="multipart/form-data">
            @foreach(config('forms.job') as $job_form_field)
                @include('parts.forms.'.$job_form_field['type'], $job_form_field)
            @endforeach

            <div class="col-md-4 col-form-label text-md-right">
                <input class="btn btn-primary" type="submit" value="Add job">
            </div>
        </form>
    </div>
@endsection
