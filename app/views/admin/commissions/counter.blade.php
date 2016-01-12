@extends ('layouts.admin')

@section ('scripts')
    @parent
    <script>
$(function () {
    $('#months').change(function(e){
        var url = window.location;
        if(url.href.indexOf('date') !=-1 ){
            window.location = url.href.replace($('#date').val(), $(this).val());
        } else {
            window.location = window.location + '?date=' + $(this).val();
        }
    });

    $('.btn-change-status').click(function(e){
        e.preventDefault();
        $('#status').val($(this).data('status'));
        $('#mass_action').submit();
    });

    $('.toggle-check-all-boxes').change(function(e){
        $('.checkbox').prop('checked', this.checked);
    });
});
    </script>
@stop

@section('content')

<ul class="nav nav-tabs" role="tablist">
    <li  @if (empty($employeeId)) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter', ['id'=> $user->id]) }}">{{ trans($langPrefix.'.employees') }}</a></li>
    @foreach ($freelancers as $freelancer)
    <li  @if ((int)$employeeId === $freelancer->id) {{ 'class="active"' }} @endif><a href="{{ route('admin.users.commissions.counter',['id'=> $user->id, 'employee'=> $freelancer->id])}}">
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
            {{ Form::select('months', $months, $date, ['style'=>'width:70%','class' => 'form-control input-sm pull-right', 'id' => 'months']) }}
            <button class="btn btn-primary btn-sm" onclick="frames['pdf'].print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
        <iframe src="{{ route('admin.users.commissions.pdf', ['id' => $user->id, 'employee' => $employeeId, 'date' => $current->format('Y-m')]) }}"  style="display:none" name="pdf"></iframe>
        <inp
        </div>
</div>

<form id="mass_action" name="mass_action" action="{{ route('admin.users.commissions.mass_status', ['id' => $user->id]) }}" method="POST">
<div  class="pull-left">
    <a href="#" data-status="suspend" class="btn-change-status btn btn-xs btn-warning" title=""><i class="fa fa-lock"></i></a>
    <a href="#" data-status="paid" class="btn-change-status btn btn-xs btn-success" title=""><i class="fa fa-check"></i></a>
    <a href="#" data-status="cancelled" class="btn-change-status btn btn-xs btn-danger" title=""><i class="fa fa-undo"></i></a>
    <input type="hidden" name="status" id="status" value="">
</div>

<table class="table table-hover table-crud pull-left">
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
        <tr @if(!empty($item->commission_status)) class="{{ $item->commission_status }}" @endif id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->booking_id }}" id="bulk-item-{{ $item->id }}"></td>
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
                @if(!empty($item->consumer_status) && $item->consumer_status == 'new') 
                    <span class="label label-success">{{ trans($langPrefix . '.status.'. $item->consumer_status) }}</span>
                @elseif(!empty($item->consumer_status) && $item->consumer_status == 'exist')
                     <span class="label label-danger">{{ trans($langPrefix . '.status.'. $item->consumer_status) }}</span>
                @endif
            </td>
            <td>
                {{ trans($langPrefix . '.status.'. $item->commisionStatus) }}
            </td>
            <td>{{ nl2br($item->ingress) }}</td>
            <td>
                <div  class="pull-right">
                    <a href="{{ route('admin.users.commissions.status', ['id' => $user->id, 'booking' => $item->booking_id, 'status'=> 'suspend']) }}" class="btn btn-xs btn-warning" title=""><i class="fa fa-lock"></i></a>
                    <a href="{{ route('admin.users.commissions.status', ['id' => $user->id, 'booking' => $item->booking_id, 'status'=> 'paid']) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-check"></i></a>
                    <a href="{{ route('admin.users.commissions.status', ['id' => $user->id, 'booking' => $item->booking_id, 'status'=> 'cancelled']) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-undo"></i></a>
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
    <div class="col-md-2 text-left">
       <a href="#" data-status="suspend" class="btn-change-status btn btn-xs btn-warning" title=""><i class="fa fa-lock"></i></a>
        <a href="#" data-status="paid" class="btn-change-status btn btn-xs btn-success" title=""><i class="fa fa-check"></i></a>
        <a href="#" data-status="cancelled" class="btn-change-status btn btn-xs btn-danger" title=""><i class="fa fa-undo"></i></a>
    </div>
    <div class="col-md-8 text-right">
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
</form>

@if(App::environment() !== 'tobook' && Config::get('varaa.commission_style') !== 'tobook')
<div class="center">
    <h2>
        {{ trans('admin.commissions.paid_this_month') }}:<br/>
        {{ number_format($paid, 2) }}{{ $currencySymbol }}
    </h2>
</div>
<div class="center">
    <h2>
        {{ trans('admin.commissions.payment_pending') }}:<br/>
        {{ number_format($pending, 2) }}{{ $currencySymbol }}
    </h2>
</div>
@endif

@if(App::environment() === 'tobook' || Config::get('varaa.commission_style') === 'tobook')
    @include($langPrefix . '.' .'pdf-footer-tobook', [
        'steadyCommision'            => $steadyCommision,
        'paidDepositCommission'      => $paidDepositCommission,
        'newConsumerCommission'      => $newConsumerCommission,
        'totalCommission'            => $totalCommission,
        'totalPaidDepositCommission' => $totalReceiveFromPaygate
    ])
@endif

<br/>
<div class="center">
{{ Form::open(['route' => ['admin.users.commissions.send_report', $user->id, 'employee' => ((!empty($employeeId) ? $employeeId : 0))], 'class' => 'center form-horizontal', 'role' => 'form']) }}
  <div class="form-group {{ Form::errorCSS('treatment_type_id', $errors) }}">
        <label for="email_address" class="col-sm-2 control-label">{{ trans('admin.commissions.email_monthly_report') }}:</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">@</span>
                {{ Form::text('email_address', (isset($employee)) ? $employee->account:'', ['class' => 'form-control input-sm', 'id' => 'email_address']) }}
             </div>
            {{ Form::errorText('email_address', $errors) }}
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('category_id', $errors) }}">
        <label for="email_title" class="col-sm-2 control-label">{{ trans('admin.commissions.email_title') }}:</label>
        <div class="col-sm-5">
             {{ Form::text('email_title', (isset($employee)) ? $employee->account:'', ['class' => 'form-control input-sm', 'id' => 'email_title']) }}
            {{ Form::errorText('email_title', $errors) }}
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('category_id', $errors) }}">
        <label for="email_content" class="col-sm-2 control-label">{{ trans('admin.commissions.email_content') }}:</label>
        <div class="col-sm-5">
             {{ Form::textarea('email_content', (isset($employee)) ? $employee->account:'', ['class' => 'form-control input-sm', 'id' => 'email_content']) }}
            {{ Form::errorText('email_content', $errors) }}
        </div>
    </div>
     <div class="form-group {{ Form::errorCSS('category_id', $errors) }}">
        <label for="email_content" class="col-sm-2 control-label"></label>
        <div class="col-sm-5">
        {{ Form::hidden('date', $current->format('Y-m'), ['id' => 'date']) }}
        <button type="submit" class="btn btn-primary btn-sm" id="btn-send-report">{{ trans('common.send') }}</button>
        </div>
    </div>
     {{ Form::close() }}
</div>
@stop
