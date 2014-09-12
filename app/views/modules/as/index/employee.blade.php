@extends ('modules.as.layout')

@section('main-classes') as-wrapper @stop

@section ('content')
<?php
    $selectedDate = $date->toDateString();
    $dayOfWeek = $date->dayOfWeek;
?>
<div class="container alert alert-info">
    <p><strong>Etusivu</strong></p>
    <p>Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.</p>
</div>

<div class="container as-date-nav">
    <div class="col-md-2">
        <div class="input-group">
            <input type="text" data-index-url="{{ route('as.employee') }}" id="calendar_date" class="form-control date-picker" value="{{ $selectedDate }}">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
    </div>
    <div class="col-md-8">
        <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> Carbon\Carbon::today()->toDateString()]) }}" class="btn btn-default">Tänään</a>
        <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> Carbon\Carbon::tomorrow()->toDateString()]) }}" class="btn btn-default">Huomenna</a>

        <div class="btn-group">
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> with(clone $date)->subWeek()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-fast-backward"></i></a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> with(clone $date)->subDay()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-backward"></i></a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> with(clone $date)->addDay()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-forward"></i></a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> with(clone $date)->addWeek()->toDateString()]) }}" class="btn btn-link"><i class="fa fa-fast-forward"></i></a>
        </div>

        <div class="btn-group">
            <?php
                $startOfWeek = with(clone $date)->startOfWeek();
                $endOfWeek = with(clone $date)->endOfWeek();
            ?>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::MONDAY) btn-primary @endif">Ma</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::TUESDAY) btn-primary @endif">Ti</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::WEDNESDAY) btn-primary @endif">Ke</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::THURSDAY) btn-primary @endif">To</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::FRIDAY) btn-primary @endif">Pe</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $startOfWeek->addDay()->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SATURDAY) btn-primary @endif">La</a>
            <a href="{{ route('as.employee', ['id'=> $employeeId, 'date'=> $endOfWeek->toDateString()]) }}" class="btn btn-default @if($dayOfWeek === Carbon\Carbon::SUNDAY) btn-primary @endif">Su</a>
        </div>
    </div>

    <div class="col-md-2 text-right">
        <button class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"> Tulosta</i></button>
    </div>
</div>

<div class="container-fluid row-no-padding">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($employees as $employee)
        <li class="@if($employee->id === intval($employeeId)) active @endif">
            <a href="{{ route('as.employee', ['id'=> $employee->id ]) }}">{{ $employee->name }}</a>
        </li>
        @endforeach
    </ul>
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
    <div class="as-calendar col-lg-11 col-md-11 col-sm-11 col-xs-11">
        @foreach ($weekDaysFromDate as $weekDay => $weekDate)
        <div class="as-col">
            <ul>
                <li class="as-col-header">{{ $weekDate }} ( {{ $weekDay }})</li>
                @foreach ($workingTimes as $hour)
                     @foreach (range(0, 45, 15) as $minuteShift)
                     <?php $slotClass = $selectedEmployee->getSlotClass($weekDate, $hour, $minuteShift); ?>
                    <li data-booking-date="{{ $weekDate }}" data-employee-id="{{ $selectedEmployee->id }}" data-start-time="{{ sprintf('%02d:%02d', $hour, $minuteShift) }}" href="#select-action" class="fancybox {{ $slotClass }}">
                        @if(strpos(trim($slotClass), 'booked') === 0)
                            <?php $booking = $selectedEmployee->getBooked($weekDate, $hour, $minuteShift); ?>
                            @if($booking != null)
                            <span class="customer-tooltip"title="">{{ $booking->consumer->first_name }} ({{ $booking->bookingServices[0]->service->description }})</span>
                            <a href="#" class="pull-right"><i class="fa fa-plus"></i></a>
                            @else
                                &nbsp;
                            @endif
                          @elseif(strpos(trim($slotClass), 'freetime') === 0)
                            <?php $freetime = $selectedEmployee->getFreetime($weekDate, $hour, $minuteShift); ?>
                             @if($freetime !== null)
                                <span class="customer-tooltip"title="{{ $freetime->description }}">{{ $freetime->description }}</span>
                                @if(strval($freetime->start_at) == sprintf('%02d:%02d:00', $hour, $minuteShift))
                                    <a ref="#" data-confirm="{{ trans('as.employees.confirm.delete_freetime') }}" data-action-url="{{ route('as.employees.freetime.delete') }}" data-freetime-id="{{ $freetime->id }}" class="btn-delete-employee-freetime pull-right"><i class="fa fa-remove"></i></a>
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
@include('modules.as.index.common')
</div>
@stop
