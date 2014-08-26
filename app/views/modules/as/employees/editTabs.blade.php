<ul class="nav nav-tabs" role="tablist">
    @if (Route::currentRouteName() === 'as.employees.edit')
    <li class="active"><a>{{ trans('as.services.edit_employeee') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.defaultTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.defaultTime', $employee->id)}}">{{ trans('as.employees.defaultTime') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.customTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.customTime')}}">{{ trans('as.employees.customTime') }}</a></li>
    @endif
</ul>
