<div class="row">
    <div class="col-sm-12">
    @foreach($cart->details as $item)
        <div class="item better" id="as-cart-item-{{ $item->id }}">
            @if(!empty($item->model))
            <h4>
                <strong>{{ $item->model->service_name }}</strong>
                <a href="{{ route('as.bookings.service.remove.in.cart') }}" data-hash="{{ $hash }}" data-cart-id="{{ $cart->id }}" data-cart-detail-id="{{ $item->id }}" data-uuid="{{ $item->model->uuid }}" class="as-remove-cart pull-right"><i class="glyphicon glyphicon-remove text-danger"></i></a>
            </h4>
            <h5>{{ $item->model->employee_name }}</h5>
            <dl class="dl-horizontal">
                <dt>{{ trans('as.embed.layout_2.date') }}</dt>
                <dd>{{ str_standard_to_local($item->model->datetime) }}, {{ $item->model->instance->plainStartTime->format('H:i') }} &ndash; {{ $item->model->instance->plainEndTime->format('H:i') }}</dd>
                @if ((bool)$user->asOptions['hide_prices'] === false)
                <dt>{{ trans('as.embed.layout_2.price') }}</dt>
                <dd>{{ $item->price }}{{ Settings::get('currency') }}</dd>
                @endif
            </dl>
            @endif
        </div>
    @endforeach
    </div>
</div>
