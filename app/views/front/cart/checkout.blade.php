@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('home.cart.checkout') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('home.cart.checkout') }}</h1>

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

                @include('front.cart.el.details', ['cart' => $cart])

                @if ($cart && $cart->isEmpty() === false && $user->is_business === false)
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-success text-uppercase comfortaa" id="btn-submit">{{ trans('home.cart.process') }} <i class="fa fa-check-circle"></i></button>
                </div>
                @endif
            </div>
        </div>
        {{ Form::close() }}

    </div>
</div>
@stop
