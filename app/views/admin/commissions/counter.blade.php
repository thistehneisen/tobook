@extends ('layouts.admin')

@section ('scripts')
    @parent
    <script>
$(function () {
    $('#months').change(function(e){
        window.location = window.location + '?date=' + $(this).val();
    });
});
    </script>
@stop

@section('content')

<ul class="nav nav-tabs" role="tablist">
    <li  @if (empty($employeeId)) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter', ['id'=> $user->id]) }}">{{ trans($langPrefix.'.employees') }}</a></li>
    @foreach ($freelancers as $freelancer)
    <li  @if ($employeeId === $freelancer->id) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter',['id'=> $user->id, 'employee'=> $freelancer->id])}}">
        {{ htmlspecialchars($freelancer->name) }}
    </a></li>
    @endforeach
</ul>
<br>

 <div class="form-group row">
        <div class="col-sm-3 hidden-print"><a href="{{ route('admin.users.commissions.counter', ['id'=> $user->id, 'employee'=> $employeeId,'date'=> with(clone $current->startOfMonth())->subMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.prev')) }}</a></div>
        <div class="col-sm-3 hidden-print">
           {{ Str::upper(trans(strtolower('common.' . $current->format('F')))); }}
        </div>
        <div class="col-sm-3 hidden-print"><a href="{{ route('admin.users.commissions.counter', ['id'=> $user->id, 'employee'=> $employeeId, 'date'=> with(clone $current->startOfMonth())->addMonth()->format('Y-m') ])}}">{{ Str::upper(trans('common.next')) }}</a></div>
        <div class="col-sm-3 hidden-print">
            <button class="btn btn-primary pull-right" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
            {{ Form::select('months', $months, $date, ['style'=>'width:70%','class' => 'form-control input-xs', 'id' => 'months']) }}
        </div>
</div>

<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes check-all" data-checkbox-class="checkbox"></th>
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
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}" id="bulk-item-{{ $item->id }}"></td>
            <td>{{ $item->created_at->format('d.m.Y') }}</td>
            <td>{{ $item->name }}</td>
            <td class="number">{{ $currencySymbol }}{{ $item->total_price }}</td>
            <td class="number">{{ $currencySymbol }}{{ $item->total_price * $commissionRate }}</td>
            <td>
                {{ trans($langPrefix . '.status.'. $item->commisionStatus) }}
            </td>
            <td>{{ nl2br($item->ingress) }}</td>
            <td>
                <div  class="pull-right">
                    <a href="{{ route('admin.users.commissions.counter', ['id' => $user->id]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                    <a href="{{ route('admin.users.commissions.counter', ['id' => $user->id]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
      @if (empty($items->getTotal()))
        <tr>
            <td colspan="{{ count($fields) + 1 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>


<div class="row">
    <div class="col-md-10 text-right">
        {{  $items->appends(Input::only('perPage'))->links() }}
    </div>

    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            {{ trans('common.per_page') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route('admin.users.commissions.counter', ['id' => $user->id, 'employee'=> $employeeId,'perPage' => 5, 'date'=> $current->format('Y-m')]) }}">5</a></li>
                <li><a href="{{ route('admin.users.commissions.counter', ['id' => $user->id, 'employee'=> $employeeId,'perPage' => 10, 'date'=> $current->format('Y-m')]) }}">10</a></li>
                <li><a href="{{ route('admin.users.commissions.counter', ['id' => $user->id, 'employee'=> $employeeId,'perPage' => 20, 'date'=> $current->format('Y-m')]) }}">20</a></li>
                <li><a href="{{ route('admin.users.commissions.counter', ['id' => $user->id, 'employee'=> $employeeId,'perPage' => 50, 'date'=> $current->format('Y-m')]) }}">50</a></li>
            </ul>
        </div>
    </div>
</div>
@stop
