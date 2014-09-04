@extends ('modules.as.layout')

@section ('content')
<?php
    $selectedDate = $date->toDateString();
    $dayOfWeek = $date->dayOfWeek;
?>
<div class="alert alert-info">
    <p><strong>{{ trans('as.index.heading') }}</strong></p>
    <p>{{ trans('as.index.description') }}</p>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" data-index-url="{{ route('as.index') }}" id="calendar_date" class="form-control date-picker">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8">
        <a href="{{ route('as.index', ['date'=> Carbon\Carbon::today()->toDateString()]) }}" class="btn btn-default">{{ trans('as.index.today') }}</a>
        <a href="{{ route('as.index', ['date'=> Carbon\Carbon::tomorrow()->toDateString()]) }}" class="btn btn-default">{{ trans('as.index.tomorrow') }}</a>

        <div class="btn-group">
            <a href="{{ route('as.index', ['date'=> with(clone $date)->subWeek()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-fast-backward"></i></a>
            <a href="{{ route('as.index', ['date'=> with(clone $date)->subDay()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-backward"></i></a>
            <a href="{{ route('as.index', ['date'=> with(clone $date)->addDay()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-forward"></i></a>
            <a href="{{ route('as.index', ['date'=> with(clone $date)->addWeek()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-fast-forward"></i></a>
        </div>

        <div class="btn-group">
            <?php
                $startOfWeek = with(clone $date)->startOfWeek();
                $endOfWeek = with(clone $date)->endOfWeek();
            ?>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::MONDAY) btn-primary @endif">{{ trans('common.short.mon') }}</a>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::TUESDAY) btn-primary @endif">{{ trans('common.short.tue') }}</a>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::WEDNESDAY) btn-primary @endif">{{ trans('common.short.wed') }}</a>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::THURSDAY) btn-primary @endif">{{ trans('common.short.thu') }}</a>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::FRIDAY) btn-primary @endif">{{ trans('common.short.fri') }}</a>
            <a href="{{ route('as.index', ['date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SATURDAY) btn-primary @endif">{{ trans('common.short.sat') }}</a>
            <a href="{{ route('as.index', ['date'=> $endOfWeek->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SUNDAY) btn-primary @endif">{{ trans('common.short.sun') }}</a>
        </div>
    </div>

    <div class="col-md-2 text-right">
        <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"> {{ trans('as.index.print') }}</i></button>
    </div>
</div>

<br>

<div class="row row-no-padding">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <ul class="as-col-left-header">
            <li class="as-col-header">&nbsp;</li>
            @foreach ($workingTimes as $hour)
                @foreach (range(0, 45, 15) as $minuteShift)
                    <li>{{ sprintf('%02d', $hour) }} : {{ sprintf('%02d', $minuteShift) }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
    <div class="as-calendar as-table-wrapper col-lg-11 col-md-11 col-sm-11 col-xs-11">
        @foreach ($employees as $employee)
        <div class="as-col">
            <ul>
                <li class="as-col-header"><a href="{{ route('as.employee', ['id'=> $employee->id ]) }}">{{ $employee->name }}</a></li>
                @foreach ($workingTimes as $hour)
                     @foreach (range(0, 45, 15) as $minuteShift)
                     <?php $slotClass = $employee->getSlotClass($selectedDate, $hour, $minuteShift); ?>
                    <li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $employee->id }}" data-start-time="{{ sprintf('%02d:%02d', $hour, $minuteShift) }}" href="#select-action" class="fancybox {{ $slotClass }}">
                        @if(strpos(trim($slotClass), 'booked') === 0)
                            <?php $booking = $employee->getBooked($selectedDate, $hour, $minuteShift); ?>
                            @if($booking != null)
                            <span class="customer-tooltip"title="">{{ $booking->consumer->first_name }} ({{ $booking->bookingServices[0]->service->description }})</span>
                            <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                            @endif
                        @else
                        varaa
                        @endif
                    </li>
                     @endforeach
                @endforeach
            </ul>
       </div>
       @endforeach
       <div class="as-col">
            <ul>
                <li class="as-col-header">Employee 1</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li>varaa</li>
                <li class="booked">
                    <span class="customer-tooltip"title="Cao Luu Bao An This is a very long text heheh">Cao Luu Bao An This is a very long text heheh (Service 3)</span>
                    <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                </li>
                <li class="booked"></li>
                <li class="booked"></li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li>varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
                <li class="inactive">varaa</li>
            </ul>
       </div>
    </div>
</div>
<div id="select-action" class="as-modal-form as-calendar-action">
<h2>Kalenteri</h2>
<table class="table table-condensed">
    <tbody>
        <tr>
            <td><input type="radio" id="freetime" value="freetime" name="action_type"></td>
            <td><label for="freetime">Lisää vapaa</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="book" value="book" name="action_type"></td>
            <td><label for="book">Tee varaus</label></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="as-submit-row" colspan="2">
            <a href="#" id="btn-continute-action" class="btn btn-primary">{{ trans('common.continue') }}</a>
            <a onclick="$.fancybox.close();" id="btn-cancel-action" class="btn btn-danger">{{ trans('common.cancel') }}</a>
        </td>
    </tr>
    </tfoot>
</table>
</div>
<input type="hidden" name="get_booking_form_url" id="get_booking_form_url" value="{{ route('as.bookings.form') }}"/>
<input type="hidden" name="get_booking_form_url" id="get_freetime_form_url" value="{{ route('as.employees.freetime.form') }}"/>
<input type="hidden" name="employee_id" id="employee_id" value=""/>
<input type="hidden" name="date" id="date" value=""/>
<input type="hidden" name="start_time" id="start_time" value=""/>
<input type="hidden" id="get_services_url" value=" {{ route('as.bookings.employee.services') }}"/>
<input type="hidden" id="get_service_times_url" value=" {{ route('as.bookings.service.times') }}"/>
<input type="hidden" id="add_service_url" value=" {{ route('as.bookings.service.add') }}"/>
<input type="hidden" id="add_booking_url" value=" {{ route('as.bookings.add') }}"/>
<input type="hidden" id="add_freetime_url" value=" {{ route('as.employees.freetime.add') }}"/>
</div>
@stop
