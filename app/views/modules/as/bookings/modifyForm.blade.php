<form id="modify_booking_form_{{ $booking->id }}" class="popover_form form-horizontal @if (!empty($extraServices)) has-extra-services @endif {{ App::getLocale() }}" action="">
    <div class="form-group">
        <label for="booking_status" class="col-sm-4">{{ trans('as.bookings.status') }}</label>
        <div class="col-sm-8">
            {{ Form::select('booking_status', $bookingStatuses, $booking->getStatusText() , array('class'=> 'form-control input-sm')); }}
        </div>
    </div>
    <div class="form-group">
        <label for="modify_times" class="col-sm-4">{{ trans('as.bookings.modify_time') }}</label>
        <div class="col-sm-8">
            <div class="input-group input-group-sm spinner" data-inc="15">
                {{ Form::text('modify_times', isset($modifyTime) ? $modifyTime : 0, ['class' => 'form-control input-sm', 'id' => 'modify_times']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    @if(!empty($resources))
    <div class="form-group">
        <label for="resource" class="col-sm-4">{{ trans('as.services.resource') }}</label>
        <div class="col-sm-8">
           {{ Form::select('resources[]', $resources, $booking->getBookingResources(true), ['class' => 'form-control input-sm select2', 'id' => 'resources', 'multiple' => 'multiple','disabled'=>'disabled']) }}
        </div>
    </div>
    @endif

    <div class="form-group">
    @if (!empty($extraServices))
        <label for="extra_services" class="col-sm-4">{{ trans('as.bookings.extra_service') }}</label>
        <div class="col-sm-8">
            {{ Form::select('extra_services[]', $extraServices, 0 , array('class'=> 'selectpicker form-control input-sm', 'multiple' => true, 'title' => trans('as.nothing_selected'))) }}
        </div>
    @endif
    </div>
    @if((int)$booking->deposit > 0 || (int)$booking->status === App\Appointment\Models\Booking::STATUS_PAID)
    <div class="form-group">
        <label for="deposit" class="col-sm-4">{{ trans('as.bookings.paid') }}</label>
        @if($booking->deposit > 0 && (int) $booking->status !== App\Appointment\Models\Booking::STATUS_PAID)
            <div class="col-sm-8">
                <p>{{ show_money($booking->deposit) }} / {{ show_money($totalPrice) }}</p>
            </div>
        @elseif((int)$booking->status === App\Appointment\Models\Booking::STATUS_PAID)
            <div class="col-sm-8">
                <p>{{ show_money($totalPrice) }} / {{ show_money($totalPrice) }}</p>
            </div>
        @endif
    </div>
    @endif

    <div class="form-group">
        <div class="col-sm-12">
            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            <?php
                $bookingStartTime = with(new Carbon\Carbon($booking->start_at))->format('H:i');
                $bookingService = $booking->bookingServices()->first();
                $employee = $bookingService->employee;
            ?>
            <a class="js-btn-view-booking btn btn-sm btn-default pull-left" href="#"
                data-start-time="{{ $bookingStartTime }}"
                data-booking-id="{{ $booking->id }}"
                data-employee-id="{{ $employee->id }}">
                <i class="glyphicon glyphicon-pencil"></i> {{ trans('as.bookings.modify_booking') }}
            </a>
            <a style="margin-left:5px" class="js-btn-cut-booking btn btn-sm btn-default pull-left" href="#"
                id="btn-cut-{{ $booking->id }}"
                data-booking-id="{{ $booking->id }}"
                data-service-id="{{ $bookingService->service->id }}"
                data-action-url="{{ route('as.bookings.cut') }}">
               <i class="fa fa-cut"></i> {{ trans('as.bookings.reschedule') }}
            </a>
            <a id="btn-cancel-{{ $booking->id }}" class="btn btn-danger btn-sm pull-right" style="margin-left:5px" onclick="$(this).closest('div.popover').popover('hide');" class="close" aria-hidden="true">{{ trans('common.cancel') }}</a>
            <a href="#" id="btn-submit-modify-form" data-booking-id="{{ $booking->id }}" data-action-url="{{ route('as.bookings.modify-form') }}" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
        </div>
    </div>
</form>
<script type="text/javascript">
    $('.selectpicker').selectpicker();
    $('select.select2').select2();
//boostrap spinner
(function (e) {e("div.spinner").each(function () {var b=e(this),c=b.find("input"),a=+b.data("inc"),d=b.attr("data-positive"),d="undefined"===typeof d?!1:"true"===d;"number"===typeof a&&a!==a&&(a=1);b.find(".btn:first-of-type").on("click",function () {c.val(+c.val()+a)});b.find(".btn:last-of-type").on("click",function () {d&&0>+c.val()-a||c.val(+c.val()-a)})})})(jQuery);
</script>
