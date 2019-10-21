@extends('layouts.app')
@section('title', $title)
@section('content')
    <h2 class="text-center">Results : start {{$start}}  to end {{$end}} </h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card" >
                <div class="card-body">
                    <h5 class="card-title">
                        <a class="btn btn-primary" data-toggle="collapse" href="#work" role="button"
                           aria-expanded="false" aria-controls="work">
                            Work count: @if($works) {{$works->count()}} @else 0 @endif
                        </a>
                    </h5>
                    <div class="collapse table-responsive" id="work">
                        <table id="work_table" class="table-bordered table">
                            <thead>
                               <th>ID</th>
                               <th>USER</th>
                               <th>IP</th>
                               <th>PORT</th>
                               <th>Parent</th>
                               <th>Date</th>
                            </thead>
                            <tbody>
                               @foreach($works as $item)
                                   <tr style="background-color: {{$item->user->color}}">
                                       <td> {{$item->task->id}} </td>
                                       <td> {{$item->user->name}} {{$item->user->email}} </td>
                                       <td>{{$item->task->ip}}</td>
                                       <td>{{$item->task->port}}</td>
                                       <td>
                                           @php
                                               if($item->parent_id){
                                                  $parent=App\Order::find($item->parent_id);
                                                  echo 'Parent ID: '.$parent->task_id;
                                               }
                                           @endphp
                                       </td>
                                       <td>{{$item->created_at}}</td>
                                   </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card" >
                <div class="card-body">
                    <h5 class="card-title">
                        <a class="btn btn-primary" data-toggle="collapse" href="#dones" role="button"
                           aria-expanded="false" aria-controls="work">
                            Done count: @if($dones) {{$dones->count()}} @else 0 @endif
                        </a>
                    </h5>
                    <div class="collapse table-responsive"  id="dones">
                            <table id="done_table" class="table-bordered table">
                                <thead>
                                <th>ID</th>
                                <th>USER</th>
                                <th>IP</th>
                                <th>PORT</th>
                                <th>Serials</th>
                                <th>Weight</th>
                                <th>Date</th>
                                </thead>
                                <tbody>
                                @foreach($dones as $item)
                                    <tr style="background-color: {{$item->user->color}}">
                                        <td> {{$item->task->id}} </td>
                                        <td> {{$item->user->name}} {{$item->user->email}} </td>
                                        <td>{{$item->task->ip}}</td>
                                        <td>{{$item->task->port}}</td>
                                        <td>
                                            @if($item->serials)
                                                @foreach($item->serials as $serial)
                                                    <a target="_blank" href="/search?q=@php echo urlencode($serial->serial); @endphp">{{$serial->serial}}</a><br>
                                                @endforeach
                                            @endif
                                            @php
                                                if($item->parent_id){
                                                   $parent=App\Order::find($item->parent_id);
                                                   echo 'Parent ID: '.$parent->task_id;
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                if($item->parent_id){
                                                   $parent=App\Order::find($item->parent_id);
                                                   echo 'Parent ID: '.$parent->task_id;
                                                }else{
                                                    echo $item->task->weight;
                                                }
                                            @endphp
                                        </td>
                                        <td>{{$item->updated_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card" >
                <div class="card-body">
                    <h5 class="card-title">
                        <a class="btn btn-primary" data-toggle="collapse" href="#faileds" role="button"
                           aria-expanded="false" aria-controls="work">
                            Faileds count: @if($faileds)   {{$faileds->count()}}  @else 0 @endif
                        </a>
                    </h5>
                    <div class="collapse table-responsive" id="faileds">
                        <table id="done_faileds" class="table-bordered table">
                            <thead>
                            <th>ID</th>
                            <th>USER</th>
                            <th>IP</th>
                            <th>PORT</th>
                            <th>Comment</th>
                            <th>Type</th>
                            <th>Date</th>
                            </thead>
                            <tbody>
                            @foreach($faileds as $item)

                                <tr style="background-color: {{$item->user->color}}">
                                    <td> {{$item->task->id}} </td>
                                    <td> {{$item->user->name}} {{$item->user->email}} </td>
                                    <td>{{$item->task->ip}}</td>
                                    <td>{{$item->task->port}}</td>
                                    <td>
                                        @php
                                             $failed_text="";
                                             $comment_text="";
                                             if($item->comment){
                                                 $comment=unserialize($item->comment);
                                                 $failed_text=$failed[$comment['select']];
                                                  $comment_text=$comment['comment'];
                                               }
                                              echo $comment_text;
                                        @endphp
                                    </td>
                                     <td>
                                         {{$failed_text}}
                                         @php
                                         if($item->parent_id){
                                            $parent=App\Order::find($item->parent_id);
                                            echo 'Parent ID: '.$parent->task_id;
                                         }
                                         @endphp
                                     </td>
                                    <td>{{$item->updated_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection