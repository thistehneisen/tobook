@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('home.cart.checkout') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('home.cart.checkout') }}</h1>

        @include ('el.messages')

        {{ Form::open(['route' => 'cart.payment', 'role' => 'form']) }}
        <div class="form-group row">
            <div class="col-sm-8 col-sm-offset-2">
                @include('front.cart.el.details', ['cart' => $cart])

                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-success text-uppercase comfortaa">{{ trans('home.cart.process') }} <i class="fa fa-check-circle"></i></button>
                </div>
            </div>
        </div>
        {{ Form::close() }}

    </div>
</div>
@stop
