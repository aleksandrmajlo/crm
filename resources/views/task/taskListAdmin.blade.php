@extends('layouts.app')
@section('title', $title)
@section('sidebar')
    @if (Auth::user()->role==1)
        @include('sidebar.menu')
    @endif
@endsection
@section('content')
    <taskread-component></taskread-component>
    <tasklist-component></tasklist-component>
    <div class="modal fade" id="SelectUser" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Set User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                     <form id="SetUsertaskForm">
                          <div class="form-group">
                              <select class="form-control" name="user_id">
                                  @foreach($users as $user)
                                      <option value="{{$user->id}}">{{$user->email}} {{$user->first_name}}</option>
                                  @endforeach
                              </select>
                          </div>
                          <input type="hidden" name="task_id">
                          <button class="btn btn-primary" type="submit">Send</button>
                     </form>
                </div>
            </div>
        </div>
    </div>
@endsection