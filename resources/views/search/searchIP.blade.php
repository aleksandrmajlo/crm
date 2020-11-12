@extends('layouts.app')
@section('title', $title)
@section('content')
<div>
    <h2 class="text-center">
        Search by IP
    </h2>
    <div class="row justify-content-md-center">
        <div class=" col col-md-8">
            <form action="{{route('searchIP') }}" method="get" role="search">
                <div class="input-group mb-4">
                    <input type="text"
                           value="{{$value}}"
                           class="form-control" name="q"
                           placeholder="Search IP">
                </div>
                <button type="submit" class="btn btn-primary ">
                    Search
                </button>
            </form>
        </div>
    </div>
    @if($tasks)
        <h2 class="text-center">Results ID</h2>
        <table id="searchTable" class="table-bordered table">
            <thead>
            <th>ID</th>
            <th>IP</th>
            <th>PORT</th>
            <th>Status</th>
            <th>Date</th>
            <th></th>
            <th></th>
            <th></th>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>
                        {{$task->id}}
                    </td>
                    <td>{{$task->ip}}</td>
                    <td>{{$task->port}}</td>
                    <td>{{$status[$task->status]}}</td>
                    <td>{{$task->created_at}}</td>
                    <td>
                        <changtask-admin task_id="{{$task->id}}"></changtask-admin>
                    </td>
                    <td>
                        <admin-logtaskother task_id="{{$task->id}}"></admin-logtaskother>
                    </td>
                    <td>
                        <link-showcomment count="{{count($task->admincomments)}}"
                                          task_id="{{$task->id}}"></link-showcomment>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <addorder-commentadmin></addorder-commentadmin>
</div>


@endsection
