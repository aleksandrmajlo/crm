@extends('layouts.app')
@section('title', 'Comments '.$doc->name)
@section('content')
    <div class="row">
        @if ($message = Session::get('success'))
            <div class="col-md-12">
                <div class="alert alert-success  alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            </div>
        @endif
        @if (Auth::user()->role==3)
            <div class="col-md-12 ">
                <form method="post" action="/doccomments" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea name="comment" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    @if($doc->status==1)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="2" name="status" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">
                                Set done
                            </label>
                        </div>
                    @endif
                    <input type="hidden" name="doc_id" value="{{$doc->id}}">
                    <button class="btn-primary btn" type="submit">Send</button>
                </form>
                <hr/>
            </div>
        @endif

        @if($log)
            <div class="col-md-12 mt-3">
                <h2 class="text-center">Log</h2>
            </div>

            <div class="col-md-12  mt-3">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Created:</h5>
                        <p class="card-text"> {{$doc->created_at}}</p>
                    </div>
                </div>
                @if($doc->user)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Worker:</h5>
                            <p class="card-text"> {{$doc->user->email}}</p>
                        </div>
                    </div>
                @endif
                @if($doc->start)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Start:</h5>
                            <p class="card-text"> {{$doc->start}}</p>
                        </div>
                    </div>
                @endif
                @if($doc->end)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">End:</h5>
                            <p class="card-text"> {{$doc->end}}</p>
                        </div>
                    </div>
                @endif
            </div>

        @endif
        <div class="col-md-12 mt-3">
            <h2 class="text-center">Comments</h2>
        </div>

        <div class="col-md-12 mt-3">
            @foreach($doccomments as $doccomment)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{$doccomment->created_at}}</h5>
                        <!--
                        <p class="card-text">
                           <pre style="border:1px solid;max-width: 200px;">{!! $doccomment->comment !!}</pre>
                        </p>
                        -->
                        <docs-copy text="{!! $doccomment->comment !!}"></docs-copy>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endsection
