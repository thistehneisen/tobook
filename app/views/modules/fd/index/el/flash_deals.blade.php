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
        @if ($item->flashDeal)
        <tr>
            <td>{{ $item->flashDeal->service->name }}</td>
            <td>{{ $item->expire->format(trans('common.format.date_time')) }}</td>
            <td>{{ $item->flashDeal->service->price }}{{ Settings::get('currency') }}</td>
            <td>{{ $item->flashDeal->discounted_price }}{{ Settings::get('currency') }} <span class="text-danger">(<i class="fa fa-caret-down"></i> {{ $item->flashDeal->discount_percent }}%)</span></td>
            <td>{{ $item->flashDeal->quantity }}</td>
            <td>{{ $item->remains }}</td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>

{{ $items->links() }}
