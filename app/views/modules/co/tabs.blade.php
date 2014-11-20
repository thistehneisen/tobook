<ul class="nav nav-tabs" role="tablist">
    <li @if (Route::currentRouteName() === 'consumer-hub.index') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub')}}">{{ trans('co.all') }}</a></li>
    <li @if (Route::currentRouteName() === 'consumer-hub.groups.index') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub.groups.index')}}">{{ trans('co.groups.all') }}</a></li>
    <li @if (Route::currentRouteName() === 'consumer-hub.upsert') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub.upsert')}}">{{ trans('co.add') }}</a></li>
    @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
        <li @if (Route::currentRouteName() === 'consumer-hub.import') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub.import')}}">{{ trans('co.import.import') }}</a></li>
    @endif
</ul>
<br>

