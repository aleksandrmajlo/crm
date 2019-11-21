@extends('layouts.app')
@section('title', $title)

@section('content')
    @if($orderfaileds)
        <div class="table-responsive">
            <table id="orderLog" class="table-bordered table">
                <thead>
                  <th>ID</th>
                  <th>USER</th>
                  <th>Status</th>
                  <th>Done(info)</th>
                  <th>Field(info)</th>
                  <th>Created</th>
                </thead>
                <tbody>
                @foreach($orderfaileds as $orderfailed)
                    <tr>
                        <td>{{$orderfailed->task_id}}</td>
                        <td>
                            @if($orderfailed->user)
                                {{$orderfailed->user->email}}  {{$orderfailed->user->fullname}}
                            @endif
                        </td>
                        <td>
                            {{$status[$orderfailed->status]}}
                        </td>
                        <td>
                            @if($orderfailed->status==3)
                                @php
                                    $done=unserialize($orderfailed->done);
                                    if($done){
                                       if(isset($done['commentall'])){
                                          ?>
                                          <p>All comment:<span><?php echo $done['commentall']; ?><span></p>
                                          <?php
                                       }
                                       if(isset($done['serials'])){
                                            foreach ($done['serials'] as $serial ){
                                                ?>
                                                <div class="row">
                                                         <dl class="">
                                                           <dt class="col-sm-12">Serial:</dt>
                                                           <dd class="col-sm-12">
                                                              <short-serial serial="<?php echo $serial['serial'];?>"></short-serial>
                                                           </dd>
                                                        </dl>

                                                        <dl class="">
                                                           <dt class="col-sm-12">Link:</dt>
                                                           <dd class="col-sm-12">
                                                              <?php echo $serial['link'];?>
                                                           </dd>
                                                        </dl>

                                                        <dl class="">
                                                           <dt class="col-sm-12">Text:</dt>
                                                           <dd class="col-sm-12">
                                                                <?php echo $serial['text'];?>
                                                           </dd>
                                                        </dl>

                                                </div>
                                                <?php
                                            }
                                       }
                                    }
                                @endphp
                            @endif
                        </td>
                        <td>
                            @if($orderfailed->status==4)
                                <p class="text-danger">
                                    Status :{{$failed_status[$orderfailed->failedstatus]}}
                                </p>
                                <p class="text-danger">
                                    Comment:<code>{{$orderfailed->comment}}</code>
                                </p>

                            @endif
                        </td>
                        <td>
                            {{$orderfailed->created_at}}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection