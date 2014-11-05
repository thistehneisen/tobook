<div class="list-group">
<?php
$selectedDate       = $date->toDateString();
$serviceLength      = (!empty($serviceTime)) ? $serviceTime->during + $extraServiceLength : $service->during + $extraServiceLength ;
$serviceTotal       = (!empty($serviceTime)) ? ($serviceTime->during + $serviceTime->after + $extraServiceLength)  : ($service->during + $service->after + $extraServiceLength);
$serviceBefore      = (!empty($serviceTime)) ? $serviceTime->before  : $service->before;
$serviceAfter       = (!empty($serviceTime)) ? $serviceTime->after  : $service->after;
$servicePrice       = (!empty($serviceTime)) ? $serviceTime->price + $extraServicePrice  : $service->price + $extraServicePrice ;
$selectedService    = (!empty($serviceTime)) ? $serviceTime  : $service;
$serviceTimeId      = (!empty($serviceTime)) ? $serviceTime->id : 'default';
?>
@foreach ($employees as $employee)
<div class="as-col">
    <ul class="header-info">
        {{ !empty($employee->avatar) ? '<li class="avatar"><img src="'.$employee->getAvatarUrl().'" class="img-responsive" /></li>': '' }}
        <li class="as-col-header"><strong>{{ $employee->name }}</strong></li>
        <li class="as-col-header"><strong>{{ $employee->description }}</strong></li>
        <li class="as-col-header"><strong>{{ trans('as.embed.guide_text') }}</strong></li>
        <li class="price-tag"><a class="btn btn-success col-xs-12"><i class="glyphicon glyphicon-tag"></i> {{ $servicePrice }} &euro;</a></li>
        <li>
            <a class="btn btn-success col-xs-12"><i class="glyphicon glyphicon-time"></i>
                {{ $serviceLength + $employee->getPlustime($service->id) }} {{ trans('common.minutes')}}
            </a>
        </li>
    </ul>
    <br>
    <ul>
        <?php $id = 1;?>
        @foreach ($workingTimes as $hour => $minutes)
            @foreach ($minutes as $minuteShift)
            <?php $slotClass = $employee->getSlotClass($selectedDate, $hour, $minuteShift, 'frontend', $selectedService); ?>
            <li data-plustime="{{ $employee->getPlustime($service->id) }}" data-employee-id="{{ $employee->id }}" data-booking-length="{{ $serviceLength }}" data-start-time="{{ sprintf('%02d:%02d', $hour, $minuteShift) }}" href="#select-action" class="slot {{ $slotClass }}" id="btn-slot-{{ $employee->id }}-{{ sprintf('%02d%02d', $hour, $minuteShift) }}">
                {{ sprintf('%02d:%02d', $hour, $minuteShift) }}
                <?php $id++;?>
            </li>
            @endforeach
        @endforeach
    </ul>
    <br>
    <ul class="footer-info">
        <form id="form-employee-{{ $employee->id }}">
        <input type="hidden" name="booking_date" id="booking_date" value="{{ $selectedDate }}">
        <input type="hidden" name="start_time" id="start_time-{{ $employee->id }}" value="">
        <input type="hidden" name="service_id" id="service_id" value="{{ $service->id }}">
        <input type="hidden" name="service_time" id="service_time" value="{{ $serviceTimeId }}">
        @foreach($extraServices as $extraService)
        <input type="hidden" name="extra_services[]" id="extra_services" value="{{ $extraService->id }}">
        @endforeach
        <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee->id }}">
        <input type="hidden" name="hash" id="hash" value="{{ $hash }}">
        </form>
        <li class="li-start-time li-start-time-{{ $employee->id }}" style="display:none"><a class="btn btn-default col-xs-12">{{ trans('as.embed.start_time')}}: <span class="start-time-{{ $employee->id }}"></span></a></li>
        <li class="li-end-time li-end-time-{{ $employee->id }}" style="display:none"><a class="btn btn-default col-xs-12">{{ trans('as.embed.end_time')}}: <span class="end-time-{{ $employee->id }}"></span></a></li>
        <li><a  data-checkout-url="{{ route('as.embed.embed',['hash' => $hash,'action' => 'checkout'])}}" data-employee-id="{{ $employee->id }}" class="btn btn-success col-xs-12 btn-make-appointment">{{ trans('as.embed.make_appointment') }}</a></li>
        <li><a class="btn btn-danger col-xs-12">{{ trans('as.embed.cancel') }}</a></li>
    </ul>
</div>
@endforeach
</div>
<input type="hidden" id="add_service_url" value="{{ route('as.bookings.service.front.add') }}" />
<input type="hidden" id="booking_length" value="{{ $serviceTotal }}"/>
<input type="hidden" id="booking_before" value="{{ $serviceBefore }}"/>
