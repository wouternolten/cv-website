@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your jobs</h1>
    @foreach($jobs as $job)
    <div class="card">
        @dump($job)
    </div>
    @endforeach
</div>
@endsection
