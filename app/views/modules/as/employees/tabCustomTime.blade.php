<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.employees.customTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.customTime')}}">{{ trans('as.employees.custom_time') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.employeeCustomTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.employeeCustomTime')}}">{{ trans('as.employees.employee_custom_time') }}</a></li>
</ul>
<br/>
