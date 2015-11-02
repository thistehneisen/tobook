<ul class="nav nav-tabs" role="tablist">
    <li @if (Route::currentRouteName() === 'admin.coupon.index') {{ 'class="active"' }} @endif><a href="{{ route('admin.coupon.index')}}">{{ trans('admin.coupon.index') }}</a></li>
    <li @if (Route::currentRouteName() === 'admin.coupon.campaigns') {{ 'class="active"' }} @endif>
	    <a href="{{ route('admin.coupon.campaigns')}}">
	        {{ trans('admin.coupon.campaigns') }}
	    </a>
    </li>
    <li @if (Route::currentRouteName() === 'admin.coupon.create') {{ 'class="active"' }} @endif><a href="{{ route('admin.coupon.create')}}">{{ trans('admin.coupon.create') }}</a></li>
    @if (Route::currentRouteName() === 'admin.coupon.campaigns.edit')
    <li class="active">
    	<a href="{{ route('admin.coupon.campaigns.edit') }}">{{ trans('admin.coupon.campaign.edit') }}</a>
    </li>
    @endif
    @if (Route::currentRouteName() === 'admin.coupon.campaigns.stats')
    <li class="active">
        <a href="{{ route('admin.coupon.campaigns.stats') }}">{{ trans('admin.coupon.campaign.stats') }}</a>
    </li>
    @endif
</ul>
