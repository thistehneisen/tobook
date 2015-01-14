<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === $routes['index']) {{ 'class="active"' }} @endif><a href="{{ route($routes['index'])}}">{{ trans($langPrefix.'.all') }}</a></li>
    <li  @if (Route::currentRouteName() === $routes['upsert']) {{ 'class="active"' }} @endif><a href="{{ route($routes['upsert'])}}">{{ trans($langPrefix.'.add') }}</a></li>
    <li  @if (Route::currentRouteName() === 'admin.users.deleted') {{ 'class="active"' }} @endif><a href="{{ route('admin.users.deleted')}}">{{ trans($langPrefix.'.deleted') }}</a></li>
</ul>
<br>
