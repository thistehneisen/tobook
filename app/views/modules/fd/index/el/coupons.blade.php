<table class="table table-hovered table-stripped">
    <thead>
        <tr>
            <th>{{ trans('fd.coupons.service_id') }}</th>
            <th>{{ trans('fd.services.price') }}</th>
            <th>{{ trans('fd.coupons.discounted_price') }}</th>
            <th>{{ trans('fd.coupons.valid_date') }}</th>
            <th>{{ trans('fd.coupons.sold') }}</th>
            <th>{{ trans('fd.coupons.total') }}</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr>
            <td>{{ $item->service->name }}</td>
            <td>{{ show_money($item->service->price) }}</td>
            <td>{{ show_money($item->discounted_price) }} <span class="text-danger">(<i class="fa fa-caret-down"></i> {{ $item->discount_percent }}%)</span></td>
            <td>{{ $item->valid_date->format(trans('common.format.date')) }}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $items->links() }}
