@extends('layouts.app')
@section('title', $title)

@section('content')
        <div class="table-responsive">
            <table id="orderLog" class="table-bordered table">
                <thead>
                   <th>LOG ID</th>
                   <th>TASK ID</th>
                   <th>Status</th>
                   <th>IP:PORT</th>
                   <th>USER</th>
                   <th>ADMIN</th>
                   <th>INFO</th>
                   <th>Created</th>
                </thead>
                <tbody>
                @foreach($orderlogs as $orderlog)
                    <tr>
                        <td>{{$orderlog['log_id']}}</td>
                        <td>{{$orderlog['id']}}</td>
                        <td>{{$orderlog['status']}}</td>
                        <td>{{$orderlog['ip']}}:{{$orderlog['port']}}</td>
                        <td>
                            @if($orderlog['user_id'])
                                {{$orderlog['user_id']}}
                            @endif
                        </td>
                        <td>
                            @if($orderlog['admin_id'])
                                {{$orderlog['admin_id']}}
                            @endif
                        </td>
                        <td>
                            @if($orderlog['text'])
                                @switch($orderlog['status_id'])
                                    @case(2)
                                    @case(4)
                                    @case(7)
                                    @if(isset($orderlog['text']['done'][ "commentall"]))
                                        <p >
                                            <strong>Comment:</strong>{{$orderlog['text']['done'][ "commentall"]}}
                                        </p>
                                    @endif
                                    @if($orderlog['text']["done"][ "serials"])
                                        @foreach($orderlog['text']["done"][ "serials"] as $serial)
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
                                                        <serial-link link="<?php echo $serial['link'];?>"></serial-link>
                                                    </dd>
                                                </dl>

                                                <dl class="">
                                                    <dt class="col-sm-12">Text:</dt>
                                                    <dd class="col-sm-12">
                                                        <?php echo $serial['text'];?>
                                                    </dd>
                                                </dl>

                                            </div>
                                        @endforeach
                                    @endif
                                    @break

                                    @case(3)
                                    @case(5)
                                    @case(6)
                                    <p>
                                        <strong>Type:</strong> {{$failed_status[$orderlog['text']['select']]}}
                                    </p>
                                    <div>
                                         {!! $orderlog['text']['comment'] !!}
                                    </div>
                                    @break

                                    @case(8)
                                    <div>
                                        {!! $orderlog['text']['comment'] !!}
                                    </div>
                                    @break

                                    @case(11)

                                    <div>
                                        {!! $orderlog['text']['comment'] !!}
                                    </div>
                                    <p>
                                        <strong>Show:</strong> {{$orderlog['text']['showcommentadmin']}}
                                    </p>
                                    @break

                                    @case(14)
                                    @case(15)
                                       <p>{{$orderlog['text']['text']}}</p>
                                    @break

                                @endswitch
                            @endif

                        </td>
                        <td class="nowrap">
                            {{$orderlog['created_at']}}
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
        <?php echo $data_orderlogs->render(); ?>
@endsection
