@extends('layouts.app')

@section('content')
<div class="spacer-20"></div>

<div class="container">
    <h1 class="title">Edit job</h1>
    <vue-job-form json_form="{{ json_encode($formData) }}" action="/jobs/{{$job->id}}" button_text="Edit Job"
        method="PUT" csrf="{!! csrf_token() !!}">
    </vue-job-form>
</div>
@endsection
