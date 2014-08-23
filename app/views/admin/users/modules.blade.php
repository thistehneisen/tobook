@extends ('layouts.admin')

@section ('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
@stop

@section ('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.{{ App::getLocale() }}.min.js"  charset="UTF-8"></script>
    <script>
$(function() {
    $('div.input-daterange').datepicker({
        language: '{{ App::getLocale() }}',
        format: 'yyyy-mm-dd'
    });
});
    </script>
@stop

@section ('content')
    {{ Form::open(['route' => ['admin.users.modules', $user->id]]) }}
    <h3 class="comfortaa">{{ trans('admin.modules.enable_module_heading') }}</h3>
    @include ('el.messages')

    @foreach ($modules as $module)
    <div class="radio">
        <label>
            {{ Form::radio('module_id', $module->id) }}
            {{ trans('dashboard.'.$module->name) }}
        </label>
    </div>
    @endforeach

    <div class="form-group">
        <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="start">
            <span class="input-group-addon">&ndash;</span>
            <input type="text" class="input-sm form-control" name="end">
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-primary">{{ trans('common.save') }}</button>
    </div>
    {{ Form::close() }}

    <h3 class="comfortaa">{{ trans('admin.modules.enabled_modules') }}</h3>
    <table class="table">
        <thead>
            <tr>
                <th>{{ trans('admin.modules.name') }}</th>
                <th>{{ trans('admin.modules.start') }}</th>
                <th>{{ trans('admin.modules.end') }}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($user->modules as $module)
            <tr class="{{ $module->is_passed === false ?: 'active' }} {{ (bool) $module->pivot->is_active === false ? 'danger' : '' }} {{ $module->is_now === true ? 'success'  : '' }}">
                <td>{{ trans('dashboard.'.$module->name) }}</td>
                <td>{{ with(new Carbon\Carbon($module->pivot->start))->format(trans('common.format.date')) }}</td>
                <td>{{ with(new Carbon\Carbon($module->pivot->end))->format(trans('common.format.date')) }}</td>
                <td>
                @if ($module->is_passed === false)
                    @if ((bool) $module->pivot->is_active === true)
                    <a href="{{ route('admin.users.modules.activation', ['userId' => $module->pivot->user_id, 'id' => $module->pivot->id]) }}" class="btn btn-danger btn-sm"> {{ trans('admin.deactivate') }}</a>
                    @else
                    <a href="{{ route('admin.users.modules.activation', ['userId' => $module->pivot->user_id, 'id' => $module->pivot->id]) }}" class="btn btn-success btn-sm"> {{ trans('admin.activate') }}</a>
                    @endif
                @else
                    <em>{{ trans('admin.modules.err_time_passed') }}</em>
                @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
