@extends ('layouts.admin')

@section ('content')

<h3 class="comfortaa text-success">
@if (isset($item))
    {{ trans('admin.edit_heading', ['model' => $modelName, 'id' => $item->id]) }}
@else
    {{ trans('admin.create_heading', ['model' => str_singular($modelName)]) }}
@endif
</h3>

@include ('el.messages')

{{ Form::open(['to' => $formAction, 'class' => 'form-horizontal']) }}
@foreach ($model->visible as $field)
    @if ($field !== 'id')
<div class="form-group">
    <label class="col-sm-2 control-label" for="{{ $field }}">{{ studly_case($field) }}</label>
    <div class="col-sm-10">
        {{ Form::text($field, Input::get($field, isset($item->$field) ? $item->$field : ''), ['class' => 'form-control', 'id' => $field]) }}
    </div>
</div>
    @endif
@endforeach

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        <a href="{{ route('admin.crud.index', ['model' => $modelName]) }}" class="btn btn-link">{{ trans('common.back') }}</a>
    </div>
</div>

{{ Form::close() }}
@stop
