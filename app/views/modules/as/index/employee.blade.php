@extends ('modules.as.layout')

@section('main-classes') as-wrapper @stop

@section ('content')
<?php
    $selectedDate = $date->toDateString();
    $dayOfWeek = $date->dayOfWeek;
    $routeName = 'as.employee';
?>
<div class="container alert alert-info">
    <p><strong>Etusivu</strong></p>
    <p>Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.</p>
</div>

@include('modules.as.index._date_nav')

<div class="container-fluid row-no-padding">
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($employees as $employee)
        <li class="@if(intval($employee->id) === intval($employeeId)) active @endif">
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
        <?php unset($selectedDate); ?>
        @foreach ($weekDaysFromDate as $weekDay => $selectedDate)
        <div class="as-col">
            <ul>
                <li class="as-col-header">{{ $selectedDate }} ({{ $weekDay }})</li>
                @foreach ($workingTimes as $hour)
                    @foreach (range(0, 45, 15) as $minuteShift)
                        <?php $slotClass = $selectedEmployee->getSlotClass($selectedDate, $hour, $minuteShift); ?>
                        @include('modules.as.index._calendar')
                    @endforeach
                @endforeach
            </ul>
       </div>
       @endforeach
    </div>
</div>
@include('modules.as.index._modals')
</div>
@stop
