@if (!$cart || $cart->isEmpty())
    <div class="alert alert-info">{{ trans('home.cart.empty_long') }}</div>
@else
<table class="table table-striped">
    <tbody>
        @foreach ($cart->details as $detail)
            @if ($detail->model !== null)
            <tr class="cart-detail" id="cart-detail-{{ $detail->id }}">
                <td>{{{ $detail->name }}}</td>
                <td>{{ show_money($detail->price) }}</td>
                <td>
                    <a class="js-btn-cart-remove" data-detail-id="{{ $detail->id }}" href="{{ route('cart.remove', ['id' => $detail->id]) }}"><i class="fa fa-close text-danger hidden-on-thankyou"></i></a>
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
    <tfoot class="hidden-on-thankyou">
        <tr>
            <td class="text-right">{{ trans('home.cart.total') }}</td>
            <td><strong>{{ show_money($cart->total) }}</strong></td>
            <td></td>
        </tr>
    </tfoot>
</table>
@endif
