@extends('layouts.app')
@section('title', $title)
@section('content')

    <dashbord-admin></dashbord-admin>

    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Admin Comments</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="adminComments">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Admin</th>
                                <th>Text</th>
                                <th>Date</th>
                                <th>Viewed user</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>{{$comment->task_id}}</td>
                                    <td>{{$comment->user->email}}</td>
                                    <td><code>{{$comment->commentadmin}}</code></td>
                                    <td>{{$comment->created_at}}</td>
                                    <td>
                                        @if($comment->viewed==1)
                                            Yes
                                        @else
                                            Not
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
