@extends ('layouts.default')

@section ('content')
{{ Form::open($opt['form']) }}
    <!-- Fields -->
    @foreach ($fields as $name => $field)
        <div class="form-group {{ Form::errorCSS($name, $errors) }}">
            {{ Form::label($name, $field['label'], ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
            <div class="col-sm-6">
        @if ($field['type'] === 'password') {{ Form::$field['type']($name, ['class' => 'form-control']) }}
        @elseif ($field['type'] === 'radio')
            <div class="radio">
                <label>{{ Form::radio($name, 'true', Input::get($name, isset($item) ? $item->$name : true)) }} {{ trans('common.yes') }}</label>
            </div>
            <div class="radio">
                <label>{{ Form::radio($name, 'false', Input::get($name, isset($item) ? $item->$name : false)) }} {{ trans('common.no') }}</label>
            </div>
        @else {{ Form::$field['type']($name, Input::get($name, isset($item) ? $item->$name : ''), ['class' => 'form-control']) }}
        @endif
            <!-- Validation error -->
            {{ Form::errorText($name, $errors) }}
            </div>
        </div>
    @endforeach

    <!-- Action buttons -->
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
