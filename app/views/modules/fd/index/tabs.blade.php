<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="{{ $tab === 'sold-flash-deals' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'sold-flash-deals']) }}" role="tab">{{ trans('fd.flash_deals.sold') }}</a></li>
    <li class="{{ $tab === 'sold-coupons' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'sold-coupons']) }}" role="tab">{{ trans('fd.coupons.sold') }}</a></li>
    <li class="{{ $tab === 'active-flash-deals' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'active-flash-deals']) }}" role="tab">{{ trans('fd.flash_deals.active') }}</a></li>
    <li class="{{ $tab === 'active-coupons' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'active-coupons']) }}" role="tab">{{ trans('fd.coupons.active') }}</a></li>
    <li class="dropdown {{ starts_with($tab, 'expired') ? 'active' : '' }}">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('fd.expired') }} <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('fd.index', ['tab' => 'expired-flash-deals']) }}" role="tab">{{ trans('fd.flash_deals.index') }}</a></li>
            <li><a href="{{ route('fd.index', ['tab' => 'expired-coupons']) }}" role="tab">{{ trans('fd.coupons.index') }}</a></li>
        </ul>
    </li>
</ul>
<br>
