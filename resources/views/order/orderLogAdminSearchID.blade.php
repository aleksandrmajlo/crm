@extends('layouts.app')
@section('title', $title)

@section('content')

    <h2 class="text-center">
        Search by ID
    </h2>
    <div class="row justify-content-md-center">
        <div class=" col col-md-8">
            <form action="{{route('orderLogID') }}" method="get" role="search">
                <div class="input-group mb-4">
                    <input type="text"
                           value="{{$value}}"
                           class="form-control" name="id"
                           placeholder="Search ID">
                </div>
                <button type="submit" class="btn btn-primary ">
                    Search
                </button>
            </form>
        </div>
    </div>

    @if($orderlogs)
        <div class="table-responsive">
            <table class="table-bordered table">
                <thead>
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
                                       <p>
                                           <strong>Comment:</strong> {{$orderlog['text']['comment']}}
                                       </p>
                                    @break

                                    @case(8)
                                       <p>
                                           <strong>Comment:</strong> {{$orderlog['text']['comment']}}
                                       </p>
                                    @break

                                    @case(11)

                                       <p>
                                           <strong>Comment:</strong> {{$orderlog['text']['comment']}}
                                       </p>
                                       <p>
                                           <strong>Show:</strong> {{$orderlog['text']['showcommentadmin']}}
                                       </p>
                                    @break

                                    @case(14)
                                    @case(15)
                                       <p>
                                            {{$orderlog['text']['text']}}
                                       </p>
                                    @break

                                @endswitch
                            @endif

                        </td>

                        <td>
                            {{$orderlog['created_at']}}
                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    @endif
@endsection