@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
    {{--<task></task>--}}
    <taskread-component></taskread-component>
    <tasklist-component></tasklist-component>
@endsection