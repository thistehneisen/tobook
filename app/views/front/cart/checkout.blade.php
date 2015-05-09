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
                @if ($user->is_business)
                <div class="alert alert-warning">
                    <p><strong>{{ trans('common.notice') }}</strong></p>
                    <p>{{ trans('home.cart.err.business') }}</p>
                </div>
                @endif

                @include ('el.messages')

                @if(!(bool) Settings::get('deposit_payment'))
                    @include('front.cart.el.details', ['cart' => $cart])
                @else
                    @include('front.cart.el.details-deposit', ['cart' => $cart])
                @endif

                @if ($cart && $cart->isEmpty() === false && $user->is_business === false)
                    <div class="text-center">
                    @if((bool) Settings::get('deposit_payment'))
                        <button type="submit" name="submit" class="btn btn-lg btn-success text-uppercase comfortaa" id="btn-submit" value="deposit_payment">{{ trans('home.cart.pay_deposit') }} <i class="fa fa-check-circle"></i></button>
                    @endif
                        <button type="submit" name="submit" class="btn btn-lg btn-success text-uppercase comfortaa" id="btn-submit" value="payment">{{ trans('home.cart.pay_whole') }} <i class="fa fa-check-circle"></i></button>
                    </div>
                @endif
            </div>
        </div>
        {{ Form::close() }}

    </div>
</div>
@stop
