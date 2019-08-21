@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
         @include('sidebar.menu')
    @endif
@endsection
@section('content')
    <tasklistuser-component></tasklistuser-component>
@endsection