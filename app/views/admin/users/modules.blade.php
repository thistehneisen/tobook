{{ Form::open(['route' => ['admin.users.modules', $user->id]]) }}
@foreach ($modules as $name => $value)
    @if ($value['enable'])
    <div class="form-group checkbox">
        <label>
            {{ Form::checkbox('modules[]', $name, $user->hasModule($name)) }} {{ trans('dashboard.'.$name) }}
        </label>
    </div>
    @endif
@endforeach

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
    </div>

{{ Form::close() }}
