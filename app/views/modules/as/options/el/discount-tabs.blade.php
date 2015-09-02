<ul class="nav nav-tabs" role="tablist">
    <li @if($page==='last-minute')class="active"@endif><a href="{{ route('as.options.discount', 'last-minute')}}">{{ trans('as.options.discount.last-minute') }}</a></li>
    <li @if($page ==='discount')class="active"@endif><a href="{{ route('as.options.discount', 'discount')}}">{{ trans('as.options.discount.discount') }}</a></li>
</ul>
