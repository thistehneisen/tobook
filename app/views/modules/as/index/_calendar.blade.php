<?php
    $booking   = $selectedEmployee->getBooked($selectedDate, $hour, $minuteShift);
    $bookingId = !empty($booking) ? $booking->id : -1;
?>
<li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $selectedEmployee->id }}" data-start-time="{{ sprintf('%02d:%02d', (int)$hour, $minuteShift) }}" href="#select-action" class="{{ $slotClass }}" @if($cutId==$bookingId) style="background-color: grey" @endif>
    @if(strpos(trim($slotClass), 'booked') === 0)
        @if($booking !== null)
            <?php $tooltip = $booking->getCalendarTooltip();?>
            @if(strpos($slotClass, 'slot-booked-head') !== false)
            <a href="{{ route('as.bookings.modify-form') }}" class="btn-plus btn-popover popup-ajax customer-tooltip" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click" title="{{{ $tooltip }}}">
                @if(!empty($booking->firstBookingService()))
                    @if($booking->firstBookingService()->is_requested_employee)
                    <i class="fa fa-check-square-o"></i>
                    @endif
                @endif
                {{ $booking->getConsumerName() }} {{ $booking->getServiceDescription() }}
            </a>
            @else
            <a href="{{ route('as.bookings.modify-form') }}" class="btn-popover popup-ajax hidden-print" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click">&nbsp;</a>
            @endif
        @endif
    @elseif(strpos(trim($slotClass), 'freetime') === 0)
        <?php $freetime = $selectedEmployee->getFreetime($selectedDate, $hour, $minuteShift); ?>
        @if($freetime !== null)
            <span>{{ $freetime->description !== '' ? $freetime->description : trans('as.employees.free_time') }}</span>
            @if(strval($freetime->start_at) === sprintf('%02d:%02d:00', $hour, $minuteShift))
                <a href="#" data-confirm="{{ trans('as.employees.confirm.delete_freetime') }}" data-action-url="{{ route('as.employees.freetime.delete') }}" data-freetime-id="{{ $freetime->id }}" class="btn-delete-employee-freetime"><i class="fa fa-remove"></i></a>
            @endif
        @endif
    @endif
</li>
