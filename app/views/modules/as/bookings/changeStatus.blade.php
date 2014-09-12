<div class="as-calendar-extra-service">
    <h2>{{ trans('as.bookings.change_status')}}</h2>
    <form id="add_change_status_form">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
            {{ Form::select('booking_status', $bookingStatuses, $booking->getStatusText() , array('class'=> 'selectpicker form-control input-sm')); }}
            </select>
            <input type="hidden" name="booking_id" value="{{ $booking->id }}"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
            <a href="#" id="btn-change-status" data-action-url="{{ route('as.bookings.change-status') }}" class="btn btn-primary btn-sm pull-right">{{ trans('common.save') }}</a>
            </div>
        </div>
    </div>
    </form>
</div>
