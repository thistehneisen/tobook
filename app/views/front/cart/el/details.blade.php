@if (!$cart || $cart->isEmpty())
    <div class="alert alert-info">{{ trans('home.cart.empty_long') }}</div>
@else
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
            <td><strong>{{ $cart->total }}&euro;</strong></td>
        </tr>
    </tfoot>
</table>
@endif
