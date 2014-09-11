@extends ('layouts.dashboard')

@section('content')
<h1 class="comfortaa">{{ trans('co.edit_heading', ['id' => $consumer->id]) }}</h1>

{{ Form::open(['route' => ['co.edit', $consumer->id], 'class' => 'form-horizontal', 'role' => 'form']) }}
    @foreach ($fields as $name => $field)
        <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
        <div class="form-group {{ Form::errorCSS($name, $errors) }}">
            {{ Form::label($name, $field['label'], ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
        @if ($type === 'password')
            {{ Form::$type($name, ['class' => 'form-control']) }}
        @else
            {{ Form::$type($name, Input::get($name, $consumer->$name), ['class' => 'form-control input-sm']) }}
        @endif
            {{ Form::errorText($name, $errors) }}
            </div>
        </div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}

@stop
