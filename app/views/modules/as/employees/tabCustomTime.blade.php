<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.employees.customTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.customTime')}}">{{ trans('as.employees.workshifts') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.employeeCustomTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.employeeCustomTime')}}">{{ trans('as.employees.workshift_planning') }}</a></li>
</ul>
<br/>
