@extends('layouts.app')
@section('title', 'List docs my')
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
        <div class="col-md-12 mt-3">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Text</th>
                        <th scope="col">Status</th>
                        <th scope="col">Created</th>
                        <th scope="col">Comments</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($docs as $doc)
                        <tr class="align-content-center">
                            <td>{{$doc->id}}</td>
                            <td>{{$doc->name}}</td>
                            <td>
                                <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{$doc->id}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                                    Show/Hide
                                </a>
                                <docs-link text="{!! $doc->text !!}"></docs-link>
                                <div class="collapse" id="collapseExample{{$doc->id}}">
                                    <docs-copy text="{!! $doc->text !!}"></docs-copy>
                                </div>
                            </td>
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
                                <a target="_blank" href="/doccomments/?doc_id={{$doc->id}}&user_id={{$user->id}}" class="btn btn-outline">
                                    Comments ({{count($doc->doccomments)}})
                                </a>
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
