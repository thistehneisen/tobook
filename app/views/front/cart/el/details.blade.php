@if (!$cart || $cart->isEmpty())
    <div class="alert alert-info">{{ trans('home.cart.empty_long') }}</div>
@else
<table class="table table-striped">
    <tbody>
        @foreach ($cart->details as $detail)
        <tr class="cart-detail" id="cart-detail-{{ $detail->id }}">
            <td>{{ $detail->name }}</td>
            <td>{{ $detail->price }}&euro;</td>
            <td>
                <a class="js-btn-cart-remove" data-detail-id="{{ $detail->id }}" href="{{ route('cart.remove', ['id' => $detail->id]) }}"><i class="fa fa-close text-danger"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right">{{ trans('home.cart.total') }}</td>
            <td><strong>{{ $cart->total }}&euro;</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endif
