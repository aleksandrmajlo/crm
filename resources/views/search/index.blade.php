@extends('layouts.app')
@section('title', $title)
@section('content')
    <div>
        <h2 class="text-center">
            Search by Serials
        </h2>
        <div class="row justify-content-md-center">
            <div class=" col col-md-8">
                <form action="/search" method="get" role="search">
                    <div class="input-group mb-4">
                        <input type="text"
                               value="{{$value}}"
                               class="form-control" name="q"
                               placeholder="Search serial">
                    </div>
                    <button type="submit" class="btn btn-primary ">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <h2 class="text-center">
            Search by ID
        </h2>
        <div class="row justify-content-md-center">
            <div class=" col col-md-8">
                <form action="/search" method="get" role="search">
                    <div class="input-group mb-4">
                        <input type="text"
                               value="{{$value_id}}"
                               class="form-control" name="id"
                               placeholder="Search ID">
                    </div>
                    <button type="submit" class="btn btn-primary ">
                        Search
                    </button>
                </form>
            </div>
        </div>

        @if($serial)
            <h2 class="text-center">Results Serials</h2>
            <div class="table-responsive">
                <table class="table-bordered table">
{{--                <table id="searchTable" class="table-bordered table">--}}
                    <thead>
                    <th>ID</th>
                    <th>USER</th>
                    <th>IP</th>
                    <th>PORT</th>
                    <th>Serial</th>
                    <th>Link</th>
                    <th>Comment</th>
                    <th>Date</th>
                    <th>Comment All</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    </thead>
                    <tbody>
                    @if($serial->task)
                        <tr style="background-color:red">
                            <td>
                                {{$serial->task_id}}
                            </td>
                            <td>
                                @if($serial->user_id)
                                    {{$serial->user->name}} {{$serial->user->email}}
                                @else
                                    {{$serial->order->user->name}} {{$serial->order->user->email}}
                                @endif
                            </td>
                            <td>
                                @if($serial->ip)
                                    {{$serial->ip}}
                                @else
                                    {{$serial->task->ip}}
                                @endif

                            </td>
                            <td>
                                @if($serial->port)
                                    {{$serial->port}}
                                @else
                                    {{$serial->task->port}}
                                @endif
                            </td>
                            <td>
                                <short-serial serial="{{$serial->serial}}"></short-serial>
                            </td>
                            <td>
                                @if($serial->link!=='')
                                    <serial-link link="{{$serial->link}}"></serial-link>
                                @endif
                            </td>
                            <td>{{$serial->text}}</td>
                            <td>{{$serial->created_at}}</td>
                            <td>
                                @if($serial->order->comment)
                                    <read-more longtext="{{$serial->order->comment}}"></read-more>
                                @endif
                            </td>
                            <td>
                                <changtask-admin task_id="{{$serial->task->id}}"></changtask-admin>
                            </td>
                            <td>
                                <admin-logtaskother task_id="{{$serial->task->id}}"></admin-logtaskother>
                            </td>
                            <td>
                                <link-showcomment count="{{count($serial->task->admincomments)}}" task_id="{{$serial->task->id}}"></link-showcomment>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="10">
                                <h2 class="text-danger">Task removed!!!</h2>
                            </td>
                        </tr>
                    @endif
                    @if($serial_other)
                        @foreach($serial_other as $k=>$serial)
                            @if($serial->task)
                                <tr>
                                    <td class="nowrap">{{$serial->task->id}}</td>
                                    <td>
                                        @if($serial->user_id)
                                            {{$serial->user->name}} {{$serial->user->email}}
                                        @else
                                            {{$serial->order->user->name}} {{$serial->order->user->email}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($serial->ip)
                                            {{$serial->ip}}
                                        @else
                                            {{$serial->task->ip}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($serial->port)
                                            {{$serial->port}}
                                        @else
                                            {{$serial->task->port}}
                                        @endif
                                    </td>
                                    <td>
                                        <short-serial serial="{{$serial->serial}}"></short-serial>
                                    </td>
                                    <td>
                                        @if($serial->link!=='')
                                            <serial-link link="{{$serial->link}}"></serial-link>
                                        @else
                                        @endif</td>
                                    <td>{{$serial->text}}</td>
                                    <td>{{$serial->created_at}}</td>
                                    <td>
                                        @if($serial->order->comment)
                                            <read-more longtext="{{$serial->order->comment}}"></read-more>
                                        @endif
                                    </td>
                                    <td>
                                        <changtask-admin task_id="{{$serial->task->id}}"></changtask-admin>
                                    </td>
                                    <td>
                                        <admin-logtaskother task_id="{{$serial->task->id}}"></admin-logtaskother>
                                    </td>
                                    <td>
                                        <link-showcomment count="{{count($serial->task->admincomments)}}"  task_id="{{$serial->task->id}}"></link-showcomment>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="10">
                                        <h2 class="text-danger">Task removed!!!</h2>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        @endif

        @if($task)
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
                        <link-showcomment count="{{count($task->admincomments)}}"  task_id="{{$task->id}}">
                        </link-showcomment>
                    </td>
                </tr>
                </tbody>
            </table>
        @endif
        @if(empty($task) AND empty($serial) )
            <p class="text-uppercase text-center"><a href="/archives">Search in archive</a></p>
        @endif
        <addorder-commentadmin form="1"></addorder-commentadmin>
        <admin-update></admin-update>
    </div>
@endsection
