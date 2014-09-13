@extends ('modules.as.layout')

@section('main-classes') as-wrapper @stop

@section ('content')
<?php
    $selectedDate = $date->toDateString();
    $dayOfWeek = $date->dayOfWeek;
    $routeName = 'as.index';
?>
<div class="container alert alert-info">
    <p><strong>{{ trans('as.index.heading') }}</strong></p>
    <p>{{ trans('as.index.description') }}</p>
</div>

@include('modules.as.index._date_nav')

<div class="container-fluid row-no-padding">
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
    <div class="as-calendar col-lg-11 col-md-11 col-sm-11 col-xs-11">
        @foreach ($employees as $selectedEmployee)
        <div class="as-col">
            <ul>
                <li class="as-col-header"><a href="{{ route('as.employee', ['id'=> $selectedEmployee->id ]) }}">{{ $selectedEmployee->name }}</a></li>
                @foreach ($workingTimes as $hour => $minutes)
                    @foreach ($minutes as $minuteShift)
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
