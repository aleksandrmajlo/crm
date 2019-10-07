@extends('layouts.app')
@section('title', $title)
@section('content')
    <p>
        @lang('search.text')
    </p>
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

    @if($serial)
        <h2 class="text-center">Results</h2>
        <table class="table-bordered table">
            <thead>
               <th>ID</th>
               <th>IP</th>
               <th>PORT</th>
               <th>Serial</th>
               <th>Link(comment)</th>
               <th>Text(comment)</th>
               <th>Status</th>
               <th>Date</th>
            </thead>
            <tbody>
                  @php
                     $order_id=$serial->order_id;
                     $color="";
                     if($order_id){
                        $order='App\Order'::where('id',$order_id)->first();
                        $color=$order->user->color;
                     }

                  @endphp
                  <tr style="background-color: {{$color}}">
                      <td>
                         {{$serial->task->id}}</td>
                      <td>{{$serial->task->ip}}</td>
                      <td>{{$serial->task->port}}</td>
                      <td>{{$serial->serial}}</td>
                      <td>{{$serial->link}}</td>
                      <td>{{$serial->text}}</td>
                      <td>{{$status[$serial->task->status]}}</td>
                      <td>{{$serial->order->updated_at}}</td>
                  </tr>
                @if($serial_other)
                    @foreach($serial_other as $serial)
                        <tr>
                            <td>{{$serial->task->id}}</td>
                            <td>{{$serial->task->ip}}</td>
                            <td>{{$serial->task->port}}</td>
                            <td>{{$serial->serial}}</td>
                            <td>{{$serial->link}}</td>
                            <td>{{$serial->text}}</td>
                            <td>{{$status[$serial->task->status]}}</td>
                            <td>{{$serial->order->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif($serial_other)
            </tbody>
        </table>

    @endif

@endsection