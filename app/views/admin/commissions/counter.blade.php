@extends ('layouts.admin')

@section('content')

<ul class="nav nav-tabs" role="tablist">
    <li  @if (1) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter')}}">{{ trans($langPrefix.'.employees') }}</a></li>
    @foreach ($freelancers as $freelancer)
    <li  @if (0) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter')}}">
        {{ htmlspecialchars($freelancer->name) }}
    </a></li>
    @endforeach
</ul>
<br>


<table class="table table-hover table-crud">
    <thead>
        <tr>
            @foreach ($fields as $field)
            @if($field == 'price' || $field == 'commission')
            <th class="number">{{ trans($langPrefix . '.'. $field) }}</th>
            @else
            <th>{{ trans($langPrefix . '.'. $field) }}</th>
            @endif
            @endforeach
            <th>&nbsp;</th>
        </tr>
    </thead>
     <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td>{{ $item->created_at->format('d.m.Y') }}</td>
            <td>{{ $item->name }}</td>
            <td class="number">{{ $currencySymbol }}{{ $item->total_price }}</td>
            <td class="number">{{ $currencySymbol }}{{ $item->total_price * $commissionRate }}</td>
            <td>
                {{ trans($langPrefix . '.status.'. $item->commisionStatus) }}
            </td>
            <td>{{ $item->notes }}</td>
            <td>
                <div  class="pull-right">
                    <a href="{{ route('admin.users.commissions.counter', ['id' => $user->id]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                    <a href="{{ route('admin.users.commissions.counter', ['id' => $user->id]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
      @if (empty($items->count()))
        <tr>
            <td colspan="{{ count($fields) + 1 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>
@stop
