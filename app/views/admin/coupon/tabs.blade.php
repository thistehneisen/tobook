<ul class="nav nav-tabs" role="tablist">
    <li @if (Route::currentRouteName() === 'admin.coupon.index') {{ 'class="active"' }} @endif><a href="{{ route('admin.coupon.index')}}">{{ trans('admin.coupon.index') }}</a></li>
    <li @if (Route::currentRouteName() === 'admin.coupon.campaigns') {{ 'class="active"' }} @endif>
	    <a href="{{ route('admin.coupon.campaigns')}}">
	        {{ trans('admin.coupon.campaigns') }}
	    </a>
    </li>
    <li @if (Route::currentRouteName() === 'admin.coupon.create') {{ 'class="active"' }} @endif><a href="{{ route('admin.coupon.create')}}">{{ trans('admin.coupon.create') }}</a></li>
</ul>
