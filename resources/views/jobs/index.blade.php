@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your jobs</h1>
    @foreach($jobs as $job)
    <div class="card">
        <ul class="list-group">
            <li class="list-group-item"><b>{{$job->company->name}}</b> (<a
                    href="{{$job->company->url}}">{{$job->company->url}}</a>)</li>
            <li class="list-group-item">Function Name: {{$job->function_name}}</li>
            <li class="list-group-item">Year: {{$job->start_date}} - {{$job->end_date ?? 'now'}}</li>
            <li class="list-group-item">City: {{$job->company->city}}</li>
            <li class="list-group-item">Responsibilities: {{$job->responsibilities}}</li>
            {{-- TODO: WRITE OUT TAGS--}}
            {{-- <li class="list-group-item">Tags: {{ explode($job->tags->pluck('title'), ',') }}</li> --}}

            @dump(implode(",", $job->tags->pluck('name')))
        </ul>
    </div>
    @endforeach
</div>
@endsection
