@extends ('layouts.admin')

@section('content')
<h3>{{ trans('admin.nav.settings') }}</h3>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        {{ implode('', $errors->all('<li>:message</li>')) }}
    </ul>
</div>
@endif

{{ Form::open(['route' => 'admin.settings', 'class' => 'form-horizontal']) }}

@foreach ($controls as $field)
    <div class="form-group">
        <label class="control-label col-sm-3">{{ trans('admin.settings.'.$field->name) }}</label>
        <div class="col-sm-6">{{ $field->render() }}</div>
    </div>
@endforeach

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button class="btn btn-sm btn-primary" type="submit">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
