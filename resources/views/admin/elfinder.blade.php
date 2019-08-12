<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>
    <div class="col-sm-8 elfinder-group {{$class}}">
				<div class="elfinder-group__inner">
					@include('admin::form.error')
					<input type="hidden" id="{{$id}}" class="elfinder-input" placeholder="{{$placeholder}}"
								{!! $attributes !!} name="{{$name}}" value="{{ old($column, $value) }}">

					<img src="{{Storage::disk('public')->url(old($column, $value))}}" alt="" id="thumb_{{$id}}" width="100" height="100">

					<a class="popup_selector btn btn-success" data-inputid="{{$id}}">Выберите изображение</a>
					<button class="btn btn-danger btn-clear" data-resset="img">Удалить изображение</button>

					@include('admin::form.help-block')
				</div>
    </div>
</div>
