<table class="table table-hover">
    <thead>
        <tr>
        @if ($service === 'as')
            <th>UUID</th>
            <th>{{ trans('co.date') }}</th>
            <th>{{ trans('co.start_at') }}</th>
            <th>{{ trans('co.end_at') }}</th>
            <th>{{ trans('co.services') }}</th>
            <th>{{ trans('co.notes') }}</th>
            <th>{{ trans('common.created_at') }}</th>
        @elseif ($service === 'lc')
            <th>{{ trans('co.date') }}</th>
            <th>{{ trans('co.action') }}</th>
            <th>{{ trans('loyalty-card.shop') }}</th>
        @endif
        </tr>
    </thead>
    <tbody>
        @if ($service === 'as')
            @foreach ($history as $value)
            <tr>
                <td>{{ $value->uuid }}</td>
                <td>{{ $value->date }}</td>
                <td>{{ $value->start_at }}</td>
                <td>{{ $value->end_at }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->notes }}</td>
                <td>{{ $value->created_at }}</td>
            </tr>
            @endforeach
        @elseif ($service === 'lc')
            @foreach ($history as $value)
            <tr>
                <td>{{ $value->created_at }}</td>
                @if ($value->offer_id === null && $value->voucher_id === null)
                <td>{{ trans('co.give_points', ['points' => $value->point]) }}</td>
                @elseif ($value->offer_id === null && $value->point < 0)
                <td>{{ trans('loyalty-card.use_voucher') }} {{ $value->name }}</td>
                @elseif ($value->voucher_id === null && $value->stamp === 1)
                <td>{{ trans('loyalty-card.add_stamp') }} {{ $value->name }}
                @else
                <td>{{ trans('loyalty-card.use_offer') }} {{ $value->name }}
                @endif
                <td>{{  $value->business_name }} </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
