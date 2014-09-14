<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="{{ $tab === 'sold-flash-deals' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'sold-flash-deals']) }}" role="tab">Sold flash deals</a></li>
    <li class="{{ $tab === 'sold-coupons' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'sold-coupons']) }}" role="tab">Sold coupons</a></li>
    <li class="{{ $tab === 'active-flash-deals' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'active-flash-deals']) }}" role="tab">Active flash deals</a></li>
    <li class="{{ $tab === 'active-coupons' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'active-coupons']) }}" role="tab">Active coupons</a></li>
    <li class="{{ $tab === 'expired' ? 'active' : '' }}"><a href="{{ route('fd.index', ['tab' => 'expired']) }}" role="tab">Expired coupons/flash deals</a></li>
</ul>
<br>
