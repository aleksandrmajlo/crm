@extends('layouts.app')
@section('title', 'List docs')
@section('content')
    <div class="row ">
           @if(!$show)
            <div class="col-md-12">
                <div class="alert alert-warning  alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>
                        You already have one material in your work and needs to be completed.
                    </strong>
                </div>
            </div>
           @else
            <div class="col-md-12 mt-3">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Created</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($docs as $doc)
                            <tr>
                                <td>{{$doc->id}}</td>
                                <td>{{$doc->name}}</td>
                                <td>
                                    {{$doc->created_at}}
                                </td>
                                <td>
                                    <form method="post" action="/docs_user_add">
                                        {{csrf_field()}}
                                        <input type="hidden" name="doc_id" value="{{$doc->id}}">
                                        <button type="submit" class="btn btn-outline">Add</button>
                                    </form>
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
           @endif
    </div>
@endsection
