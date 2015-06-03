@extends ('layouts.admin')
@section ('scripts')
{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
@stop

@section('content')

<h3>{{ trans('admin.nav.booking_terms') }}</h3>

{{ Form::open(['route' => 'admin.booking.terms', 'class' => 'form-horizontal well']) }}

@foreach ($controls as $field)
    <div class="form-group">
        <label class="control-label col-sm-1">{{ trans('admin.settings.'.$field->name) }}</label>
        <div class="col-sm-11">{{ $field->render() }}</div>
    </div>
@endforeach

    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-11">
            <button class="btn btn-sm btn-primary" type="submit">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
