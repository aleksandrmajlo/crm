@extends('layouts.app')
@section('title', $title)
@section('content')

    <dashbord-admin></dashbord-admin>

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">General Statistics Tasks</h2>

            <p class="text-primary">All : {{$statistics['all']}} </p>
            <p class="text-secondary">Free : {{$statistics['free']}}</p>
            <p class="text-secondary">Work : {{$statistics['work']}}</p>
            <p class="text-success">Done : {{$statistics['done']}}</p>
            <p class="text-danger">Failed : {{$statistics['failed']}}</p>
        </div>
    </div>


@endsection
