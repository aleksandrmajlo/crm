@extends('layouts.app')
@section('title', $title)
@section('content')
    <dashbord-admin></dashbord-admin>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">General Statistics Tasks</div>
                <div class="card-body">
                    <p class="text-primary">All : {{$statistics['all']}} </p>
                    <p class="text-secondary">Free : {{$statistics['free']}}</p>
                    <p class="text-secondary">Work : {{$statistics['work']}}</p>
                    <p class="text-success">Done : {{$statistics['done']}}</p>
                    <p class="text-danger">Failed :{{$statistics['failed']}} </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr>
            <a class="btn-primary btn" href="/clearTask" target="_blank">Удалить пустые серийники</a>
        </div>
        <div class="col-md-12">
            <p>
                Заметил в базе серийники, задания для которых ,задания для которых были удалены.
                Что б почистить это нужно перейти по кнопке выше
            </p>
        </div>
    </div>
@endsection
