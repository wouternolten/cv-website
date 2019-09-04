@extends('layouts.app')

@section('content')
<div class="spacer-20"></div>

<div class="container">
    <h1 class="title">Add job to CV</h1>
    <vue-add-job json_form="{{ json_encode($formData) }}">
    </vue-add-job>
</div>
@endsection
