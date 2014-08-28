@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.options.general.heading') }}</strong></p>
    <p>{{ trans('as.options.general.info') }}</p>
</div>

{{ Form::open(['class' => 'form-horizontal']) }}
@foreach ($fields as $field)
    <div class="form-group form-group-sm">
        <label class="control-label col-sm-2">{{ $field->getName() }}</label>
        <div class="col-sm-6">
        {{ $field }}
        </div>
    </div>
@endforeach
{{ Form::close() }}
@stop
