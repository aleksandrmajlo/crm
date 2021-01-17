@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')

    <taskread-admin date="{{$date}}"></taskread-admin>

    <div class="mt-5">
        @include('task.taskFilter')
    </div>

    <addorder-commentadmin></addorder-commentadmin>
@endsection
