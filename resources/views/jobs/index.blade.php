@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your jobs</h1>
    @foreach($jobs as $job)
    <div class="card mb--lg">
        <ul class="list-group">
            <li class="list-group-item"><b>{{$job->company->name}}</b> (<a
                    href="{{$job->company->url}}">{{$job->company->url}}</a>)</li>
            <li class="list-group-item">Function Name: {{$job->function_name}}</li>
            <li class="list-group-item">Year: {{$job->start_date}} - {{$job->end_date ?? 'now'}}</li>
            <li class="list-group-item">City: {{$job->company->city}}</li>
            <li class="list-group-item">Responsibilities: {{$job->responsibilities}}</li>
            <li class="list-group-item">Tags: {{ implode(",", $job->tags->pluck('name')->toArray()) }}</li>
            <li class="list-group-item">
                <a href="/jobs/{{$job->id}}/edit" class="btn btn-primary">Edit</a>
                <form method="POST" action="/jobs/{{$job->id}}" class="form--inline">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
            </li>
        </ul>
    </div>
    @endforeach
</div>
@endsection
