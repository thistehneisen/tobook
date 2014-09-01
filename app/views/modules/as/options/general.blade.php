@extends ('modules.as.layout')

@section ('content')

<h3>{{ trans('as.options.'.$page.'.index') }}</h3>

<div class="alert alert-info">
    <p><strong>{{ trans('as.options.general.heading') }}</strong></p>
    <p>{{ trans('as.options.general.info') }}</p>
</div>

{{ Form::open(['class' => 'form-horizontal']) }}
@foreach ($fields as $field)
    <div class="form-group form-group-sm">
        <label class="control-label col-sm-3">{{ trans('as.options.'.$page.'.'.$field->getName()) }}</label>
        <div class="col-sm-6">
        {{ $field }}
        </div>
    </div>
@endforeach

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
