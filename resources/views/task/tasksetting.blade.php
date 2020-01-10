@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    <sidebar-admin></sidebar-admin>
    @include('sidebar.menu')
@endsection
@section('content')
    <tasksetting-component></tasksetting-component>
@endsection