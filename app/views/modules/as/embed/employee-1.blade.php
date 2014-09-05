<div class="list-group">
<?php $selectedDate = $date->toDateString();?>
 @foreach ($employees as $employee)
<div class="as-col">
    <ul class="header-info">
        <li class="as-col-header"><strong>{{ $employee->name }}</strong></li>
        <li><a class="btn btn-success col-xs-12"><i class="glyphicon glyphicon-tag"></i> {{ $service->price }} &euro;</a></li>
        <li><a class="btn btn-success col-xs-12"><i class="glyphicon glyphicon-time"></i> {{ $service->length }} {{ trans('common.minutes')}}</a></li>
    </ul>
    <br>
    <ul>
        @foreach ($workingTimes as $hour)
             @foreach (range(0, 45, 15) as $minuteShift)
             <?php $slotClass = $employee->getSlotClass($selectedDate, $hour, $minuteShift); ?>
            <li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $employee->id }}" data-start-time="{{ sprintf('%02d:%02d', $hour, $minuteShift) }}" href="#select-action" class="fancybox {{ $slotClass }}">
                {{ sprintf('%02d:%02d', $hour, $minuteShift) }}
            </li>
             @endforeach
        @endforeach
    </ul>
    <br>
    <ul class="footer-info">
        <li><a class="btn btn-success col-xs-12">Make an Appointment</a></li>
        <li><a class="btn btn-danger col-xs-12">Cancel</a></li>
    </ul>
</div>
@endforeach
</div>
