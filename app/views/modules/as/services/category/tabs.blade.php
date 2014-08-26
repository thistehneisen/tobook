<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.services.categories.index') {{ 'class="active"' }} @endif><a href="{{ route('as.services.categories.index')}}">{{ trans('as.services.all_categories') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.services.categories.upsert') {{ 'class="active"' }} @endif><a href="{{ route('as.services.categories.upsert')}}">{{ trans('as.services.add_category') }}</a></li>
</ul>
<br>
