<table class="table table-hovered table-stripped">
    <thead>
        <tr>
            <th>{{ trans('fd.services.name') }}</th>
            <th>{{ trans('fd.flash_deals.date') }}</th>
            <th>{{ trans('fd.services.price') }}</th>
            <th>{{ trans('fd.flash_deals.discounted_price') }}</th>
            <th>{{ trans('fd.flash_deals.quantity') }}</th>
            <th>{{ trans('fd.flash_deal_dates.remains') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->flashDeal->service->name }}</td>
            <td>{{ $item->expire->format(trans('common.format.date_time')) }}</td>
            <td>&euro;{{ $item->flashDeal->service->price }}</td>
            <td>&euro;{{ $item->flashDeal->discounted_price }} <span class="text-danger">(<i class="fa fa-caret-down"></i> {{ $item->flashDeal->discount_percent }}%)</span></td>
            <td>{{ $item->flashDeal->quantity }}</td>
            <td>{{ $item->remains }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
