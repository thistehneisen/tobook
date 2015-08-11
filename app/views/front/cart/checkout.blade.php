@extends ('layouts.default')

@section ('title')
    {{ trans('home.cart.checkout') }}
@stop

@section ('styles')
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style(asset('packages/alertify/css/themes/default.min.css')) }}
    <style>
.payment-wrapper {
    position: relative;
    margin-top: 10px;
    padding: 15px;
}
.payment-wrapper .overlay {
    display: none;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    background-color: rgba(0, 242, 102, 0.65);
    height: 100%;
    z-index: 2;
    color: #333;
    font-size: 2em;
    text-align: center;
    padding-top: 5%;
}

    </style>
@stop

@section ('scripts')
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script>
$(function () {
    $('#btn-payment-venue').on('click', function (e) {
        e.preventDefault();
        var $form = $('#frm-payment');
        var fnOnOkOverlay = function () {
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                dataType: 'JSON',
                data: {submit: 'venue'}
            }).then(function (res) {
                $('#js-overlay')
                    .html(res.message.join(''))
                    .show();
                var counter = 9;
                var id = setInterval(function () {
                    $('#as-counter').html(counter);
                    if (counter-- === 0) {
                        clearInterval(id);
                        window.location = '{{ route('home') }}';
                    }
                }, 1000);
            }).fail(function (res) {
                alertify.set('notifier','position', 'top-right');
                alertify.error(res.responseJSON.message);
            });
        };

        var fnOnOkThankYou = function() {
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                dataType: 'JSON',
                data: {submit: 'venue'}
            }).then(function (res) {
                $('.hidden-on-thankyou').remove();
                $('.show-on-thankyou').show();
                var counter = 9;
                var id = setInterval(function () {
                    if (counter-- === 0) {
                        clearInterval(id);
                        window.location = '{{ route('home') }}';
                    }
                }, 1000);
            }).fail(function (res) {
                alertify.set('notifier','position', 'top-right');
                alertify.error(res.responseJSON.message);
            });
        }

        var env = $form.data('env');
        fnOnOk = (env === 'tobook' || env === 'stag') ? fnOnOkThankYou : fnOnOkOverlay;
        fnOnOk();
    });
});
    </script>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <div class="payment-wrapper">
            <div id="js-overlay" class="overlay">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repudiandae aliquid unde sint laboriosam, adipisci libero, asperiores nisi assumenda eum voluptatibus facilis amet fuga eaque doloribus cupiditate, nostrum pariatur, accusantium eius!</div>
            <h1 class="comfortaa orange text-center hidden-on-thankyou">{{ trans('home.cart.checkout') }}</h1>
            <h4 class="comfortaa orange text-center hidden-on-thankyou">{{ trans('home.cart.checkout_message') }}</h4>

            @include ('front.cart.el.show-on-thankyou', ['hidden' => true])

            {{ Form::open(['route' => 'cart.payment', 'role' => 'form', 'id' => 'frm-payment', 'data-env' => App::environment() ]) }}
            <div class="form-group row">
                <div class="col-sm-8 col-sm-offset-2">

                    @include ('el.messages')

                    @if((bool) Settings::get('deposit_payment')
                        && in_array('deposit', $business->payment_options)
                        && (App::environment() === 'tobook'))
                        @include('front.cart.el.details-deposit', ['cart' => $cart])
                    @else
                        @include('front.cart.el.details', ['cart' => $cart])
                    @endif

                    @if ($cart && $cart->isEmpty() === false)
                        <div class="text-center hidden-on-thankyou">
                        @foreach ($business->payment_options as $option)
                            <button id="btn-payment-{{ $option }}" type="submit" name="submit" class="btn btn-default btn-success text-uppercase comfortaa" value="{{ $option }}">{{ trans('home.cart.pay_'.$option) }} <i class="fa fa-check-circle"></i></button>
                        @endforeach
                        </div>
                    @endif
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
