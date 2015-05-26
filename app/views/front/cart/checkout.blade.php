@extends ('layouts.default')

@section('title')
    {{ trans('home.cart.checkout') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('home.cart.checkout') }}</h1>
        @if((bool) Settings::get('deposit_payment'))
        <h4 class="comfortaa orange text-center">{{ trans('home.cart.deposit_message') }}</h4>
        @endif
        {{ Form::open(['route' => 'cart.payment', 'role' => 'form']) }}
        <div class="form-group row">
            <div class="col-sm-8 col-sm-offset-2">

                @include ('el.messages')

                @if(!(bool) Settings::get('deposit_payment'))
                    @include('front.cart.el.details', ['cart' => $cart])
                @else
                    @include('front.cart.el.details-deposit', ['cart' => $cart])
                @endif

                @if ($cart && $cart->isEmpty() === false)
                    <div class="text-center">
                    @foreach ($business->payment_options as $option)
                        <button id="btn-payment-{{ $option }}" type="submit" name="submit" class="btn btn-lg btn-success text-uppercase comfortaa" value="{{ $option }}">{{ trans('home.cart.pay_'.$option) }} <i class="fa fa-check-circle"></i></button>
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
        {{ Form::close() }}

    </div>
</div>
@stop
