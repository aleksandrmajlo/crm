@extends('layouts.app')
@section('title', 'Docs '.$status)
@section('content')
    <div class="row ">
        @if ($message = Session::get('success'))
            <div class="col-md-12">
                <div class="alert alert-success  alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            </div>
        @endif
        <div class="col-md-12 ">
            <form method="post" action="/docs" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class=" mb-3">
                    <input required type="file" name="file" class="" id="customFile">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect2">Worker</label>
                    <select class="form-control" name="user_id" id="exampleFormControlSelect2">
                        <option disabled selected value>not</option>
                        @foreach($users as $user)
                            <option value="{{$user['user_id']}}">{{$user['email']}}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-primary btn" type="submit">Send</button>
            </form>
        </div>
    </div>
    <hr>
    <div class="row ">
        <div class="col-md-12 mt-3">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col">Log</th>
                        <th scope="col">Worker</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($docs as $doc)
                        <tr>
                            <td>{{$doc->id}}</td>
                            <td>{{$doc->name}}</td>
                            <td>
                                @if($doc->status==1)
                                    work
                                @else
                                    end
                                @endif
                            </td>
                            <td>
                                {{$doc->created_at}}
                            </td>
                            <td>
                                <a target="_blank" href="/doccomments/?doc_id={{$doc->id}}&log=true" class="btn btn-outline">Comments ({{count($doc->doccomments)}})</a>
                            </td>
                            <td>
                                @if($doc->user)
                                    {{$doc->user->email}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-12">
            {{ $docs->links() }}
        </div>

    </div>
@endsection
