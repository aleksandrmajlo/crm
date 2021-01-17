@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
    <h2 class="text-center mb-5 mt-5">List Task</h2>
    @include('task.taskFilter')
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
            <tr>
                <th>ID</th>
                <th></th>
                <th>IP PORT</th>
                <th>DONAIN\LOGIN</th>
                <th>PASSWORD</th>
                <th>COST</th>
                <th>STATUS</th>
                <th style="max-width: 30px;"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr
                    @if($task->color)
                        style="background-color: {{$task->color}}"
                    @endif
                >
                    <td>
                        {{$task->id}}</br>
                        {{$task->created_at->format('Y-m-d')}}
                    </td>
                    <td>
                        <img src="{{$task->flag}}" alt="">
                    </td>
                    <td>
                        {{$task->ip}}:{{$task->port}}
                    </td>
                    <td>
                        <span>{{$task->domain}}\</span>{{$task->login}}
                    </td>
                    <td>{{$task->password}}</td>
                    <td>
                        {{$task->weight}}
                    </td>
                    <td class="text-center">{{$status[$task->status]}}</td>

                    <td style="max-width: 30px;">
                        <log-task task_id="{{$task->id}}" reload="false"></log-task>
                    </td>

                    <td>
                        <changtask-admin task_id="{{$task->id}}" reload="1"></changtask-admin>
                    </td>
                    <td style="max-width: 30px;">
                      <link-showcomment   task_id="{{$task->id}}" count="{{count($task->admincomments)}}" ></link-showcomment>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center">
        {{ $tasks->links() }}
    </div>
    <addorder-commentadmin></addorder-commentadmin>
    <admin-update></admin-update>
@endsection
