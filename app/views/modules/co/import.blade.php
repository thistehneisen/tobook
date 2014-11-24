@extends ('modules.co.layout')

@section ('content')
    @include ('el.messages')

{{ Form::open(['route' => ['consumer-hub.doImport'], 'class' => 'form-horizontal well', 'role' => 'form', 'files' => true]) }}

    <div class="form-group {{ Form::errorCSS('upload', $errors) }}">
        <label for="upload" class="col-sm-2 control-label">{{ trans('co.import.upload_csv') }}</label>
        <div class="col-sm-5">
           {{ Form::file('upload', ['id' => 'upload']) }}
           {{ Form::errorText('upload', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary" id="btn-import">{{ trans('co.import.import') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
