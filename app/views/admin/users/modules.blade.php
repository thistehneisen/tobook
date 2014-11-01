@extends ('layouts.admin')

@section('content')
{{ Form::open(['route' => ['admin.users.modules', $user->id]]) }}
    <h3 class="comfortaa">{{ trans('admin.modules.enable_module_heading') }}</h3>
    <div class="alert alert-info">
        <p>By default, all modules are available for user. Uncheck if you want to disable a module.</p>
    </div>
    @include ('el.messages')

@foreach ($modules as $name => $value)
    <div class="form-group checkbox">
        <label>
            {{ Form::checkbox('modules[]', $name, $user->hasModule($name)) }} {{ trans('dashboard.'.$name) }}
        </label>
    </div>
@endforeach

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
    </div>

{{ Form::close() }}
@stop
