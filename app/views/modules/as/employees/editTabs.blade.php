<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.employees.edit') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.edit', array('id'=>$employeeId))}}">{{ trans('as.services.edit_employeee') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.defaultTime.get') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.defaultTime.get', array('id'=>$employeeId))}}">{{ trans('as.employees.defaultTime') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.employees.customTime') {{ 'class="active"' }} @endif><a href="{{ route('as.employees.customTime')}}">{{ trans('as.employees.customTime') }}</a></li>
</ul>
