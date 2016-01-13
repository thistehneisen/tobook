<table style="width: 100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            @foreach ($fields as $field)
            @if($field == 'price' || $field == 'commission')
            <th class="number">{{ trans($langPrefix . '.'. $field) }}</th>
            @else
            <th>{{ trans($langPrefix . '.'. $field) }}</th>
            @endif
            @endforeach
        </tr>
    </thead>
     <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr @if(!empty($item->commission_status)) class="{{ $item->commission_status }}" @endif id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td>{{ with(new Carbon\Carbon($item->created))->format('d.m.Y') }}</td>
            <td>{{ with(new Carbon\Carbon($item->date))->format('d.m.Y') }}</td>
            @if(empty($employeeId))
            <td>{{ $item->name }}</td>
            @endif
            <td>{{ $item->consumer_name }}</td>
            <td class="number">{{ $item->total_price }}{{ $currencySymbol }}</td>
            <td>
                @if(!empty($item->commission_status)) {{ trans($langPrefix . '.status.'. $item->commission_status) }} @endif
            </td>
            <td>
                @if(!empty($item->consumer_status)) {{ trans($langPrefix . '.status.'. $item->consumer_status) }} @endif
            </td>
            <td>
                {{ trans($langPrefix . '.status.'. $item->commisionStatus) }}
            </td>
            <td>{{ nl2br($item->ingress) }}</td>
        </tr>
    @endforeach
      @if (empty($items->count()))
        <tr>
            <td colspan="{{ count($fields) + 1 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>
