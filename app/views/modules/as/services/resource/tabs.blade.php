<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.services.resources') {{ 'class="active"' }} @endif><a href="{{ route('as.services.resources')}}">{{ trans('as.services.all_resources') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.services.resources.create') {{ 'class="active"' }} @endif><a href="{{ route('as.services.resources.create')}}">{{ trans('as.services.add_resource') }}</a></li>
    @if (Route::currentRouteName() === 'as.services.resources.edit')
    <li class="active"><a>{{ trans('as.services.edit_resource') }}</a></li>
    @endif
</ul>
