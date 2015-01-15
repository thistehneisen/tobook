<ul class="nav nav-tabs" role="tablist">
    <li @if (Route::currentRouteName() === 'as.employees.index') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.index')}}">{{ trans('as.employees.all') }}</a></li>
    <li @if (Route::currentRouteName() === 'as.employees.upsert') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.upsert', ['id' => $employee->id])}}">
        @if (isset($employee->id))
            {{ trans('as.employees.edit') }}
        @else
            {{ trans('as.employees.add') }}
        @endif
    </a></li>
@if (isset($employee->id))
    <li @if (Route::currentRouteName() === 'as.employees.defaultTime.get') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.defaultTime.get', ['id' => $employee->id])}}">{{ trans('as.employees.default_time') }}</a></li>
@endif
</ul>
