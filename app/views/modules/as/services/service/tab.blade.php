@if (!empty($service->id))
<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.services.upsert') {{ 'class="active"' }} @endif><a href="{{ route('as.services.upsert', ['id' => $service->id])}}">{{ trans('as.services.edit') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.services.customTime') {{ 'class="active"' }} @endif><a href="{{ route('as.services.customTime', ['id' => $service->id ])}}">{{ trans('as.services.custom_time') }}</a></li>
</ul>
<br>
@endif
