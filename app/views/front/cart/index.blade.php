<h5>{{ trans('home.cart.heading') }}</h5>

@include('front.cart.el.details', ['cart' => $cart])

@if ($cart && $cart->isEmpty() === false)
<div class="text-center">
    <a href="{{ route('cart.checkout') }}" class="btn btn-sm">{{ trans('home.cart.checkout') }} <i class="fa fa-arrow-right"></i></a>
</div>
@endif
