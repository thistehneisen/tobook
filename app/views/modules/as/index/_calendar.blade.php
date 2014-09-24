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
            <span class="customer-tooltip" title="{{{ $tooltip }}}">
                <a class="js-btn-view-booking" href="#"
                    data-start-time="{{ $bookingStartTime }}"
                    data-booking-id="{{ $booking->id }}"
                    data-employee-id="{{ $selectedEmployee->id }}">
                    {{{ $consumerName }}} {{{ $serviceDescription }}}
                </a>
            </span>
            <a href="#select-modify-action" class="btn-plus fancybox btn-select-modify-action" data-booking-id="{{ $booking->id }}" data-action-url="{{ route('as.bookings.extra-service-form') }}"><i class="fa fa-plus"></i></a>
        @else
            &nbsp;
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
