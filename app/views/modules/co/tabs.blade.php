<ul class="nav nav-tabs" role="tablist">
    <li @if (Route::currentRouteName() === 'consumer-hub.index') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub')}}">{{ trans('common.all') }}</a></li>
    <li @if (Route::currentRouteName() === 'consumer-hub.upsert') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub.upsert')}}">{{ trans('common.add') }}</a></li>
    @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
        <li @if (Route::currentRouteName() === 'consumer-hub.import') {{ 'class="active"' }} @endif><a href="{{ route('consumer-hub.import')}}">{{ trans('co.import.import') }}</a></li>
    @endif
</ul>
<br>

