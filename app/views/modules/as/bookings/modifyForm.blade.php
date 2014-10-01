<form id="modify_booking_form_{{ $booking->id }}" class="popover_form" action="">
    <div>
          <span class="text-info"><strong>{{ trans('as.bookings.modify_booking')}}</strong><span id="loading" style="display:none"><img src="{{ asset('assets/img/busy.gif') }}"/></span></span><button type="button" onclick="$(this).closest('div.popover').popover('hide');" class="close" data-dismiss="popover">&times;</button>
            <hr>
    </div>
    <div>
        <div class="col-sm-4"><label for="extra_services">{{ trans('as.bookings.extra_service') }}</label></div>
        <div class="col-sm-8">
            {{ Form::select('extra_services[]', $extraServices, 0 , array('class'=> 'selectpicker form-control input-sm','multiple' => true)); }}
        </div>
    </div>
    <div>
        <div class="col-sm-4"><label for="booking_status">{{ trans('as.bookings.status') }}</label></div>
        <div class="col-sm-8">
            {{ Form::select('booking_status', $bookingStatuses, $booking->getStatusText() , array('class'=> 'selectpicker form-control input-sm')); }}
        </div>
    </div>
    <div>
        <div class="col-sm-4"><label for="modify_times">{{ trans('as.bookings.modify_time') }} </label></div>
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
    <div>
        <div class="col-sm-4">&nbsp;</div>
        <div class="col-sm-8">
            <?php
                $bookingStartTime = with(new Carbon\Carbon($booking->start_at))->format('H:i');
                $bookingService = $booking->bookingServices()->first();
                $employee = $bookingService->employee;
            ?>
            <a class="js-btn-view-booking btn btn-sm btn-default" href="#"
                data-start-time="{{ $bookingStartTime }}"
                data-booking-id="{{ $booking->id }}"
                data-employee-id="{{ $employee->id }}">
                <i class="glyphicon glyphicon-pencil"></i> {{ trans('common.edit') }}
            </a>
        </div>
    </div>
    <div>
        <hr>
    </div>
    <div>
        <div class="col-sm-12">
            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            <a class="btn btn-danger btn-sm pull-right" style="margin-left:5px" onclick="$(this).closest('div.popover').popover('hide');" class="close" aria-hidden="true">{{ trans('common.cancel') }}</a>
            <a href="#" id="btn-submit-modify-form" data-booking-id="{{ $booking->id }}" data-action-url="{{ route('as.bookings.modify-form') }}" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
        </div>
    </div>
    <div>
        &nbsp;
    </div>
</form>
<script type="text/javascript">
    $('.selectpicker').selectpicker();
//boostrap spinner
(function(e){e("div.spinner").each(function(){var b=e(this),c=b.find("input"),a=+b.data("inc"),d=b.attr("data-positive"),d="undefined"===typeof d?!1:"true"===d;"number"===typeof a&&a!==a&&(a=1);b.find(".btn:first-of-type").on("click",function(){c.val(+c.val()+a)});b.find(".btn:last-of-type").on("click",function(){d&&0>+c.val()-a||c.val(+c.val()-a)})})})(jQuery);
</script>
