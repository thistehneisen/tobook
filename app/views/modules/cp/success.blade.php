@extends ('layouts.default')

@section('scripts')
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section ('styles')
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

@section('content')
@if (App::environment() !== 'tobook' && App::environment() !== 'stag')
<div class="container">
    <h1 class="comfortaa orange text-center">{{ trans('cp.success') }}</h1>

        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">

                <div class="alert alert-success">
                    <p>{{ trans('cp.success_notice') }}</p>
                </div>
            </div>
        </div>
</div>
@else
<div class="row">
    <div class="col-xs-12">
        <div class="payment-wrapper">
            @include ('front.cart.el.show-on-thankyou', ['hidden' => false])
            @if(!empty($cart))

            @if((bool) Settings::get('deposit_payment'))
            <table class="table table-striped">
                <tbody>
                    @foreach ($cart->details as $detail)
                        @if ($detail->model !== null)
                        <tr class="cart-detail" id="cart-detail-{{ $detail->id }}">
                            <td>{{{ $detail->name }}}</td>
                            <td>{{{ $detail->deposit }}}{{ Settings::get('currency') }}</td>
                            <td></td>
                            <td>{{{ $detail->price }}}{{ Settings::get('currency') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            @else
            @endif

            @endif
        </div>
    </div>
</div>
@endif
@stop
