@if (!$cart || $cart->isEmpty())
    <div class="alert alert-info">{{ trans('home.cart.empty') }}</div>
@else
<h5>Your selected products {{ $cart->id }}</h5>
<table class="table table-striped">
    <tbody>
        @foreach ($cart->details as $detail)
        <tr>
            <td>{{ $detail->name }}</td>
            <td>{{ $detail->price }}&euro;</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right">{{ trans('home.cart.total') }}</td>
            <td class="text-info"><strong>{{ $cart->total }}&euro;</strong></td>
        </tr>
    </tfoot>
</table>
<div class="text-center">
    <a href="#" class="btn btn-sm">{{ trans('home.cart.checkout') }} <i class="fa fa-arrow-right"></i></a>
</div>
@endif
