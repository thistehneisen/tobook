<h5>{{ trans('home.cart.heading') }}</h5>
@if (!$cart || $cart->isEmpty())
    <div class="alert alert-info">{{ trans('home.cart.empty_long') }}</div>
@else
    @include('front.cart.el.details', ['cart' => $cart])
<div class="text-center">
    <a href="{{ route('cart.checkout') }}" class="btn btn-sm">{{ trans('home.cart.checkout') }} <i class="fa fa-arrow-right"></i></a>
</div>
@endif
