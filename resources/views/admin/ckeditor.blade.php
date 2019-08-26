<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <div class="col-md-12 " style="padding-left: 100px;padding-right: 100px;">
        <label for="{{$id}}" >{{$label}}</label>
    </div>

    <div class="col-md-12" style="padding-left: 100px;padding-right: 100px;">
        @include('admin::form.error')
        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
        @include('admin::form.help-block')
    </div>
</div>