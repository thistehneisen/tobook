<ul class="nav nav-tabs" role="tablist">
    <li  @if (Route::currentRouteName() === 'as.services.resources.index') {{ 'class="active"' }} @endif><a href="{{ route('as.services.resources.index')}}">{{ trans('as.services.all_resources') }}</a></li>
    <li  @if (Route::currentRouteName() === 'as.services.resources.upsert') {{ 'class="active"' }} @endif><a href="{{ route('as.services.resources.upsert')}}">{{ trans('as.services.add_resource') }}</a></li>
    @if (Route::currentRouteName() === 'as.services.resources.edit')
    <li class="active"><a>{{ trans('as.services.edit_resource') }}</a></li>
    @endif
</ul>
