<div class="row">
    <div class="col-sm-12">
    @foreach($cart->details as $item)
        <div class="item better" id="as-cart-item-{{ $cart->id }}">
            <h4>
                <strong>{{ $item->model->service_name }}</strong>
                <a href="{{ route('as.bookings.service.remove.in.cart') }}" data-hash="{{ $hash }}" data-uuid="{{ $cart->id }}" class="as-remove-cart pull-right"><i class="glyphicon glyphicon-remove text-danger"></i></a>
            </h4>
            <h5>{{ $item->model->employee_name }}</h5>
            <dl class="dl-horizontal">
                <dt>{{ trans('as.embed.layout_2.date') }}</dt>
                <dd>{{ $item->model->datetime }}, {{ $item->model->start_at }} &ndash; {{ $item->model->end_at }}</dd>
                <dt>{{ trans('as.embed.layout_2.price') }}</dt>
                <dd>{{ $item->price }}&euro;</dd>
            </dl>
        </div>
    @endforeach
    </div>
</div>
