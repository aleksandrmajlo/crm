@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    <import-button></import-button>
    @include('sidebar.menu')
@endsection
@section('content')
    <task-import></task-import>
@endsection
