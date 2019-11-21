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
                    <h5 class="modal-title" id="exampleModalLabel">Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <admin-update></admin-update>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg " id="LogTask" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                     <admin-logtask></admin-logtask>
                </div>
            </div>
        </div>
    </div>


@endsection