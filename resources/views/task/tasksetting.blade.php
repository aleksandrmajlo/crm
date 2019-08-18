@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        <sidebar-admin></sidebar-admin>
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
    <tasksetting-component></tasksetting-component>
@endsection