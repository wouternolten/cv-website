@extends('layouts.app')

@section('content')
<div class="spacer-20"></div>

<div class="container">
    <h1 class="title">Add job to CV</h1>
    <vue-job-form json_form="{{ json_encode($formData) }}" action="/jobs" button_text="Add Job" method="POST"
        csrf="{!! csrf_token() !!}">
    </vue-job-form>
</div>
@endsection
