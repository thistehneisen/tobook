<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === $routes['index']) {{ 'class="active"' }} @endif><a href="{{ route($routes['index'])}}">{{ trans($langPrefix.'.all') }}</a></li>
    <li  @if (Route::currentRouteName() === $routes['upsert']) {{ 'class="active"' }} @endif><a href="{{ route($routes['upsert'])}}">
        @if (isset($item->id))
            {{ trans($langPrefix.'.edit') }}
        @else
            {{ trans($langPrefix.'.add') }}
        @endif
    </a></li>
</ul>
<br>
