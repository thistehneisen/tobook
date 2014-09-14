<table class="table table-hovered table-stripped">
    <thead>
        <tr>
            <th>{{ trans('fd.services.name') }}</th>
            <th>{{ trans('fd.services.price') }}</th>
            <th>{{ trans('fd.flash_deals.date') }}</th>
            <th>{{ trans('fd.flash_deals.discounted_price') }}</th>
            <th>{{ trans('fd.flash_deals.quantity') }}</th>
            <th>{{ trans('fd.flash_deal_dates.remains') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->service->name }}</td>
            <td>&euro;{{ $item->service->price }}</td>
            <td>{{ $item->active->date }} {{ $item->active->time }}</td>
            <td>&euro;{{ $item->discounted_price }} <span class="text-danger">(<i class="fa fa-caret-down"></i> {{ $item->discount_percent }}%)</span></td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->active->remains }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
