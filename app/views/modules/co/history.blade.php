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
        @else if ($service === 'lc')
            <th>{{ trans('loyalty-card.date') }}</th>
            <th>{{ trans('loyalty-card.action') }}</th>
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
        @endif
    </tbody>
</table>
