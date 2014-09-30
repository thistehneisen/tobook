<form id="modify_booking_form_{{ $booking->id }}" class="popover_form" action="">
    <div>
          <span class="text-info"><strong>{{ trans('as.bookings.modify_booking')}}</strong></span><button type="button" onclick="$(this).closest('div.popover').popover('hide');" class="close" data-dismiss="popover">&times;</button>
          <hr>
    </div>
    <div>
        <div class="col-sm-4"><label for="extra_services">{{ trans('as.bookings.extra_service') }}</label> </div>
        <div class="col-sm-8">
            {{ Form::select('extra_services[]', $extraServices, 0 , array('class'=> 'selectpicker form-control input-sm','multiple' => true)); }}
        </div>
    </div>
    <div>
        <div class="col-sm-4"><label for="booking_status">{{ trans('as.bookings.status') }}</label> </div>
        <div class="col-sm-8">
            {{ Form::select('booking_status', $bookingStatuses, $booking->getStatusText() , array('class'=> 'selectpicker form-control input-sm')); }}
        </div>
    </div>
    <div>
        <div class="col-sm-12">
            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            <a class="btn btn-danger btn-sm pull-right" style="margin-left:5px" onclick="$(this).closest('div.popover').popover('hide');" type="button" class="close" aria-hidden="true">{{ trans('common.cancel') }}</a>
            <a href="#" id="btn-submit-modify-form" data-booking-id="{{ $booking->id }}" data-action-url="{{ route('as.bookings.modify-form') }}" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
        </div>
    </div>
    <div>
        &nbsp;
    </div>
</form>
<script type="text/javascript">
    $('.selectpicker').selectpicker();
</script>
