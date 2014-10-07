<div class="row">
    <div class="col-sm-12">
    @foreach($cart->cartDetails as $detail)
        <?php $item = $detail->getASBookingInfo();?>
        <div class="item better" id="as-cart-item-{{ $detail->cart->id }}">
            <h4>
                <strong>{{ $item['service_name'] }}</strong>
                <a href="{{ route('as.bookings.service.remove.in.cart') }}" data-hash="{{ $hash }}" data-uuid="{{ $detail->cart->id }}" class="as-remove-cart pull-right"><i class="glyphicon glyphicon-remove text-danger"></i></a>
            </h4>
            <h5>{{ $item['employee_name'] }}</h5>
            <dl class="dl-horizontal">
                <dt>{{ trans('as.embed.layout_2.date') }}</dt>
                <dd>{{ $item['datetime'] }}, {{ $item['start_at'] }} &ndash; {{ $item['end_at'] }}</dd>
                <dt>{{ trans('as.embed.layout_2.price') }}</dt>
                <dd>{{ $item['price'] }}&euro;</dd>
            </dl>
        </div>
    @endforeach
    </div>
</div>
