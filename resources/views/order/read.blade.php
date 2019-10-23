@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
        <read-order order-id="{{$order_id}}" ></read-order>
@endsection