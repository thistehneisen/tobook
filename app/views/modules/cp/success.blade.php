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
.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.aligncenter {
  text-align: center;
}

.alignright {
  text-align: right;
}

.alignleft {
  text-align: left;
}

.clear {
  clear: both;
}
.invoice {
  margin: 40px auto;
  text-align: left;
  width: 80%;
}
.invoice td {
  padding: 5px 0;
}
.invoice .invoice-items {
  width: 100%;
}
.invoice .invoice-items td {
  border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
  border-top: 2px solid #333;
  border-bottom: 2px solid #333;
  font-weight: 700;
}
    </style>
@stop

@section('content')
@if (App::environment() !== 'tobook')
<div class="container">
    {{ $details }}
</div>
@else
<div class="row">
    <div class="payment-wrapper">
        @include ('front.cart.el.show-on-thankyou', ['hidden' => false])
        @if(!empty($cart))
        <div class="form-group row">
            <div class="col-sm-8 col-sm-offset-2">
                <table class="table table-striped">
                    <tbody>
                        @foreach ($cart->details as $detail)
                        @if ($detail->model !== null)
                        <tr class="cart-detail" id="cart-detail-{{ $detail->id }}">
                            <td>{{{ $detail->name }}}</td>
                            <td></td>
                            <td>{{{ show_money($detail->price) }}}</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endif
@stop
