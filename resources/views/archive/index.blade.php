@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
    <h2 class="text-center mb-5 mt-5">List Task</h2>
    @include('archive.search')
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
            <tr class="text-center">
                <th>ID</th>
                {{--                <th>USER</th>--}}
                {{--                <th></th>--}}
                <th>IP</th>
                <th>PORT</th>
                {{--                <th>Serial</th>--}}
                <th>Date</th>
                <th class="text-center">Log</th>
{{--                <td>Comments</td>--}}
                {{--                <th>DONAIN\LOGIN</th>--}}
                {{--                <th>PASSWORD</th>--}}
                {{--                <th>COST</th>--}}
                {{--                <th>STATUS</th>--}}
                {{--                <th style="max-width: 30px;"></th>--}}
                {{--                <th></th>--}}
                {{--                <th></th>--}}
                {{--                <th></th>--}}
            </tr>
            </thead>
            <tbody>
            @if($tasks)
                @foreach($tasks as $task)
                    <tr
                        class="text-center"
                        @if($task->color)
                        style="background-color: {{$task->color}}"
                        @endif
                    >
                        <td>
                            {{$task->id}}</br>
                            {{--                            {{$task->created_at->format('Y-m-d')}}--}}
                        </td>
                        {{--                        <td>--}}
                        {{--                            @if($task->user)--}}
                        {{--                                {{$task->user->name}} {{$task->user->email}}--}}
                        {{--                            @endif--}}
                        {{--                        </td>--}}
                        <td>
                            {{$task->ip}}
                        </td>
                        <td>
                            {{$task->port}}
                        </td>
                        <td>
                            {{$task->created_at->format('Y-m-d')}}
                        </td>
{{--                        <td>--}}
{{--                            @if($task->serials)--}}
{{--                                @foreach($task->serials as $serial)--}}
{{--                                    <short-serial serial="{{$serial}}"></short-serial>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td>
                            <log-task task_id="{{$task->id}}" reload="false" delete="true"></log-task>
                        </td>
{{--                        <td>--}}
{{--                            <link-showcomment count="{{count($task->admincomments)}}"--}}
{{--                                              task_id="{{$task->id}}">--}}
{{--                            </link-showcomment>--}}
{{--                        </td>--}}

                        {{--

                                                <td>
                                                    <span>{{$task->domain}}\</span>{{$task->login}}
                                                </td>
                                                <td>{{$task->password}}</td>
                                                <td>
                                                    {{$task->weight}}
                                                </td>
                                                <td class="text-center">{{$status[$task->status]}}</td>

                                                <td style="max-width: 30px;">
                                                    <log-task task_id="{{$task->id}}" reload="false" delete="true"></log-task>
                                                </td>
                                                <td>
                        </td>
                                                <td style="max-width: 30px;">
                                                </td>
                        --}}


                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    @if($tasks)
        <div class="d-flex justify-content-center">
            {{ $tasks->links() }}
        </div>
    @endif
    <addorder-commentadmin form="-1"></addorder-commentadmin>
    {{--    <admin-update></admin-update>--}}
@endsection
