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
            @foreach ($workingTimes as $hour => $minutes)
                @foreach ($minutes as $minuteShift)
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
                @foreach ($workingTimes as $hour => $minutes)
                     @foreach ($minutes as $minuteShift)
                     <?php $slotClass = $employee->getSlotClass($selectedDate, $hour, $minuteShift); ?>
                    <li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $employee->id }}" data-start-time="{{ sprintf('%02d:%02d', (int)$hour, $minuteShift) }}" href="#select-action" class="fancybox {{ $slotClass }}">
                        @if(strpos(trim($slotClass), 'booked') === 0)
                            <?php $booking = $employee->getBooked($selectedDate, $hour, $minuteShift); ?>
                            @if($booking !== null)
                            <span class="customer-tooltip"title="{{ $booking->consumer->getNameAttribute() }} ({{ $booking->bookingServices[0]->service->description }})">{{ $booking->consumer->getNameAttribute() }} ({{ $booking->bookingServices[0]->service->description }})</span>
                            <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                            @else
                                &nbsp;
                            @endif
                        @elseif(strpos(trim($slotClass), 'freetime') === 0)
                            <?php $freetime = $employee->getFreetime($selectedDate, $hour, $minuteShift); ?>
                             @if($freetime !== null)
                                <span class="customer-tooltip"title="{{ $freetime->description }}">{{ $freetime->description }}</span>
                                @if(strval($freetime->start_at) == sprintf('%02d:%02d:00', $hour, $minuteShift))
                                    <a href="#" data-confirm="{{ trans('as.employees.confirm.delete_freetime') }}" data-action-url="{{ route('as.employees.freetime.delete') }}" data-freetime-id="{{ $freetime->id }}" class="btn-delete-employee-freetime pull-right"><i class="fa fa-remove"></i></a>
                                @endif
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
    </div>
</div>
@include('modules.as.index.common')
</div>
@stop
