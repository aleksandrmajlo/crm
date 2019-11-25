@extends('layouts.app')
@section('title', $title)
@section('content')

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
            <table id="searchTable" class="table-bordered table">
                <thead>
                <th>ID</th>
                <th>USER</th>
                <th>IP</th>
                <th>PORT</th>
                <th>Serial</th>
                <th>Link</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Comment All</th>
                </thead>
                <tbody>


                @if($serial->task)
                    <tr style="background-color: {{$color}}">
                        <td>
                            {{$serial->task->id}}
                        </td>
                        <td>
                            {{$serial->order->user->name}} {{$serial->order->user->email}}
                        </td>
                        <td>{{$serial->task->ip}}</td>
                        <td>{{$serial->task->port}}</td>
                        <td>
                            <short-serial serial="{{$serial->serial}}"></short-serial>
                        </td>
                        <td>
                            @if($serial->link!=='')
                                <a target="_blank" href="{{$serial->link}}">{{$serial->link}}</a>
                            @else
                            @endif

                        </td>
                        <td>{{$serial->text}}</td>
                        <td>{{$status[$serial->task->status]}}</td>
                        <td>{{$serial->order->updated_at}}</td>
                        <td>
                            @if($serial->order->comment)
                                {{$serial->order->comment}}
                            @endif
                        </td>
                    </tr>
                @else
                    <tr style="background-color: {{$color}}">
                        <td colspan="10">
                            <h2 class="text-danger">Task removed!!!</h2>
                        </td>
                    </tr>
                @endif

                @if($serial_other)
                    @foreach($serial_other as $serial)
                        @if($serial->task)
                            <tr  >
                                <td>{{$serial->task->id}}</td>
                                <td>
                                    {{$serial->order->user->name}} {{$serial->order->user->email}}
                                </td>
                                <td>{{$serial->task->ip}}</td>
                                <td>{{$serial->task->port}}</td>
                                <td>
                                    <short-serial serial="{{$serial->serial}}"></short-serial>
                                </td>
                                <td>
                                    @if($serial->link!=='')
                                        <a target="_blank" href="{{$serial->link}}">{{$serial->link}}</a>
                                    @else
                                    @endif</td>
                                <td>{{$serial->text}}</td>
                                <td>{{$status[$serial->task->status]}}</td>
                                <td>{{$serial->order->updated_at}}</td>
                                <td>
                                    @if($serial->order->comment)
                                        {{$serial->order->comment}}
                                    @endif
                                </td>
                                <td>

                                </td>
                            </tr>
                        @else
                            <tr style="background-color: {{$color}}">
                                <td colspan="10">
                                    <h2 class="text-danger">Task removed!!!</h2>
                                </td>
                            </tr>
                        @endif

                    @endforeach
                @endif($serial_other)
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
            </thead>
            <tbody>

                  <tr >
                      <td>
                         {{$task->id}}
                      </td>
                      <td>{{$task->ip}}</td>
                      <td>{{$task->port}}</td>
                      <td>{{$status[$task->status]}}</td>
                      <td>{{$task->created_at}}</td>
                      <td >
                           <changtask-admin  task_id="{{$task->id}}"></changtask-admin>
                      </td>

                  </tr>
            </tbody>
        </table>
    @endif

@endsection