<?php
    $employee          = (!empty($theEmployee)) ? $theEmployee : $employee;
    $booking           = $employee->getBooked($selectedDate, $hour, $minuteShift);
    $freetime          = $employee->getFreetime($selectedDate, $hour, $minuteShift);
    $bookingId         = !empty($booking) ? $booking->id : -1;
    $slots             = !empty($booking) ? round($booking->total / 15) : 0;
    $freetimeSlots     = (int) !empty($freetime) ? round($freetime->getLength() / 15) : 0;
    $maxHeight         = !empty($booking) ? $slots * 18 : 18;
    $maxFreetimeHeight = !empty($freetime) ? $freetimeSlots * 18 : 18;
?>
<li @if(strpos(trim($slotClass), 'freetime') !== false) data-freetime-id="{{ $freetime->id }}" @endif data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $employee->id }}" data-start-time="{{ sprintf('%02d:%02d', (int)$hour, $minuteShift) }}" href="#select-action" class="{{ $slotClass }}" id="btn-slot-{{ $employee->id }}-{{ sprintf('%02d%02d', $hour, $minuteShift) }}" @if($cutId==$bookingId) style="background-color: grey" @endif>
    @if(strpos(trim($slotClass), 'booked') === 0)
        @if($booking !== null)
            @if ($booking->isShowModifyPopup())
                <?php $tooltip = $booking->getCalendarTooltip();?>
                @if(strpos($slotClass, 'slot-booked-head') !== false)
                <a href="{{ route('as.bookings.modify-form') }}" style="max-height: {{ $maxHeight }}px;" class="btn-plus btn-popover popup-ajax backend-tooltip" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click" title="{{{ $tooltip }}}">{{ $booking->getIcons() }} @if (!empty($booking->source_icon)) <i class="fa {{ $booking->source_icon }}"></i> @endif {{ $booking->getConsumerName() }} {{ $booking->getServiceDescription() }}</a>
                @else
                <a href="{{ route('as.bookings.modify-form') }}" class="btn-popover popup-ajax hidden-print" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click">&nbsp;</a>
                @endif
            @else
                @if(strpos($slotClass, 'slot-booked-head') !== false)
                <a href="javascript:void(0);">@if (!empty($booking->source_icon)) <i class="fa {{ $booking->source_icon }}"></i> @endif {{ $booking->getConsumerName() }} {{ $booking->getServiceDescription() }}</a>
                @endif
            @endif
        @endif
    @elseif(strpos(trim($slotClass), 'freetime') !== false)
        @if($freetime !== null)
            @if(strval($freetime->start_at) === sprintf('%02d:%02d:00', $hour, $minuteShift))
                <span style="max-height: {{ $maxFreetimeHeight }}px;@if(intval($maxFreetimeHeight)===18)white-space: nowrap; text-overflow: ellipsis;@endif"><a href="#" data-confirm="{{ trans('as.employees.confirm.delete_freetime') }}" data-action-url="{{ route('as.employees.freetime.delete') }}" data-freetime-id="{{ $freetime->id }}" class="btn-delete-employee-freetime"><i class="fa fa-remove"></i></a><a class="backend-tooltip" title="{{{ $freetime->description }}}">{{ $freetime->description !== '' ? $freetime->description : trans('as.employees.free_time') }}</a></span>
            @else
                <span>&nbsp;</span>
            @endif
        @endif
    @endif
</li>
