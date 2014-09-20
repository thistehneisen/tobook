{{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <h3 class="comfortaa orange">{{ trans('user.change_password') }}</h3>
    @foreach ($fields as $name => $field)
        <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
        <div class="form-group {{ Form::errorCSS($name, $errors) }}">
            {{ Form::label($name, $field['label'].Form::required($name, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
            <div class="col-sm-6">
        @if ($type === 'password') {{ Form::$type($name, ['class' => 'form-control']) }}
        @else {{ Form::$type($name, Input::get($name), ['class' => 'form-control']) }}
        @endif
            {{ Form::errorText($name, $errors) }}
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-9 text-right">
            <input type="hidden" name="tab" value="password">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}