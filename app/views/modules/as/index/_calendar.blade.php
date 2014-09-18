<li data-booking-date="{{ $selectedDate }}" data-employee-id="{{ $selectedEmployee->id }}" data-start-time="{{ sprintf('%02d:%02d', (int)$hour, $minuteShift) }}" href="#select-action" class="{{ $slotClass }}">
    @if(strpos(trim($slotClass), 'booked') === 0)
        <?php $booking = $selectedEmployee->getBooked($selectedDate, $hour, $minuteShift); ?>
        @if($booking !== null)
        <?php
            $serviceDescription = '';
            if (!empty($booking->bookingServices()->first())) {
                $serviceDescription = '('.$booking->bookingServices()->first()->service->name.')';
            }
            $bookingNote = empty($booking->notes) ? '' : '<br><br><em>'.$booking->notes.'</em>';
        ?>
        <span class="customer-tooltip"
            title="{{ with(new Carbon\Carbon($booking->start_at))->format('H:i') }}
                - {{ with(new Carbon\Carbon($booking->end_at))->format('H:i') }} <br>
                {{{ $booking->consumer->getNameAttribute() }}} <br>
                {{{ $serviceDescription }}}
                {{{ $bookingNote }}}
                ">
            <a class="js-btn-view-booking" href="#"
                data-start-time="{{ with(new Carbon\Carbon($booking->start_at))->format('H:i') }}"
                data-booking-id="{{ $booking->id }}"
                data-employee-id="{{ $selectedEmployee->id }}">
                {{{ $booking->consumer->getNameAttribute() }}} {{{ $serviceDescription }}}
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
