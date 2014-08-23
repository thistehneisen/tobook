<ul class="nav nav-tabs" role="tablist">
  <li  @if (Route::currentRouteName() === 'as.services.categories') {{ 'class="active"' }} @endif><a href="{{ route('as.services.categories')}}">{{ trans('as.services.all_categories') }}</a></li>
  <li  @if (Route::currentRouteName() === 'as.services.categories.create' || Route::currentRouteName() === 'as.services.categories.edit') {{ 'class="active"' }} @endif><a href="{{ route('as.services.categories.create')}}">{{ trans('as.services.add_category') }}</a></li>
</ul>
