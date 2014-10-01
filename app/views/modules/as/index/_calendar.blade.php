<li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $selectedEmployee->id }}" data-start-time="{{ sprintf('%02d:%02d', (int)$hour, $minuteShift) }}" href="#select-action" class="{{ $slotClass }}">
    @if(strpos(trim($slotClass), 'booked') === 0)
        <?php $booking = $selectedEmployee->getBooked($selectedDate, $hour, $minuteShift); ?>
        @if($booking !== null)
            <?php
            $bookingStartTime = with(new Carbon\Carbon($booking->start_at))->format('H:i');
            $bookingEndTime = with(new Carbon\Carbon($booking->end_at))->format('H:i');
            $consumerName = $booking->consumer->getNameAttribute();
            $bookingService = $booking->bookingServices()->first();
            $bookingServiceArray = !empty($bookingService->service->name)
                ? [$bookingService->service->name]
                : [];
            $allServices = array_merge($bookingServiceArray, $booking->getExtraServices());
            $serviceDescription = !empty($allServices)
                ? '('.implode(' + ', $allServices).')'
                : '';
            $bookingNote = empty($booking->notes) ? '' : '<br><br><em>'.$booking->notes.'</em>';

            $tooltip = sprintf(
                '%s - %s <br> %s <br> %s %s',
                $bookingStartTime, $bookingEndTime, $consumerName, $serviceDescription, $bookingNote
            );
            ?>
            @if(strpos($slotClass, 'slot-booked-head') !== false)
            <span class="customer-tooltip" title="{{{ $tooltip }}}">
                {{{ $consumerName }}} {{{ $serviceDescription }}}
            </span>
            <a href="{{ route('as.bookings.modify-form') }}" class="btn-plus popup-ajax" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click"><i class="fa fa-plus"></i></a>
            @else
           <a href="{{ route('as.bookings.modify-form') }}" class="btn-popover popup-ajax" data-booking-id="{{ $booking->id }}" data-toggle="popover" data-trigger="click">&nbsp;</a>
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
