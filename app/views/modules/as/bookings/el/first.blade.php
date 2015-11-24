<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapseOne">
        <h4 class="panel-title" id="panel-booking-info-handle">
            1. {{ trans('as.bookings.booking_info') }}
        </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    @if (isset($booking))
                    <div class="form-group row">
                        <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('common.created_at') }}</label>
                        <div class="col-sm-8">{{ str_datetime($booking->created_at) }}</div>
                    </div>
                    @endif
                    <div class="form-group row">
                        <label for="booking_uuid" class="col-sm-4 control-label">{{ trans('as.bookings.booking_id') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('uuid', $uuid , ['class' => 'form-control input-sm', 'id' => 'uuid', 'disabled'=> 'disabled']) }}
                            <input type="hidden" name="booking_uuid" id="booking_uuid" value="{{ $uuid }}"/>
                            <input type="hidden" name="booking_id" id="booking_id" value="{{ (!empty($booking) ? $booking->id : '') }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="booking_status" class="col-sm-4 control-label">{{ trans('as.bookings.status') }}</label>
                        <div class="col-sm-8">
                            {{ Form::select('booking_status', $bookingStatuses, (isset($booking)) ? $booking->getStatusText() : '', ['class' => 'form-control input-sm', 'id' => 'booking_status']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="booking_notes" class="col-sm-4 control-label">{{ trans('as.bookings.notes') }}</label>
                        <div class="col-sm-8">
                            {{ Form::textarea('booking_notes', (isset($booking)) ? $booking->notes : '', ['class' => 'form-control input-sm', 'id' => 'booking_notes']) }}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="keyword" class="col-sm-4 control-label">{{ trans('as.bookings.keyword') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('keyword', '', ['class' => 'form-control input-sm select2', 'id' => 'keyword']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="consumer_first_name" class="col-sm-4 control-label">{{ trans('as.bookings.first_name') }}  {{ Form::required('first_name', with(new Consumer)) }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('first_name', (isset($booking)) ? $booking->consumer->first_name : '', ['class' => 'form-control input-sm', 'id' => 'first_name']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_name" class="col-sm-4 control-label">{{ trans('as.bookings.last_name') }}  {{ Form::required('last_name', with(new Consumer)) }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('last_name', (isset($booking)) ? $booking->consumer->last_name : '', ['class' => 'form-control input-sm', 'id' => 'last_name']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-sm-4 control-label">{{ trans('as.bookings.email') }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('email', (isset($booking)) ? $booking->consumer->email : '', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-sm-4 control-label">{{ trans('as.bookings.phone') }}  {{ Form::required('phone', with(new Consumer)) }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('phone',(isset($booking)) ? $booking->consumer->phone : '', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-sm-4 control-label">{{ trans('as.bookings.address') }}  {{ Form::required('address', with(new Consumer)) }}</label>
                        <div class="col-sm-8">
                            {{ Form::text('address',(isset($booking)) ? $booking->consumer->address : '', ['class' => 'form-control input-sm', 'id' => 'address']) }}
                        </div>
                    </div>
                    @if((bool)$user->asOptions['show_employee_request'] == true)
                    <div class="form-group row">
                        <div class="col-sm-offset-4 col-sm-8">
                            <label for="is_requested_employee">{{ Form::checkbox('is_requested_employee', 1, (isset($firstBookingService)) ? $firstBookingService->is_requested_employee : false, ['id' => 'is_requested_employee']) }} {{ trans('as.bookings.own_customer') }}
                            </label>
                        </div>
                    </div>
                    @endif
                    @if(!empty($booking->consumer->id))
                    <div class="form-group row">
                        <div class="col-sm-offset-4 col-sm-8">
                            <a class="js-showHistory btn btn-primary" href="{{ route('bookings.history') }}" data-consumerid="{{$booking->consumer->id}}" data-service="as">{{ trans('common.history')}}</a>
                            <a id="js-show-consumer-info" class="js-show-consumer-info btn btn-primary" href="{{ route('bookings.consumer_info') }}" data-consumerid="{{ !empty($booking->consumer->id) ? $booking->consumer->id : '' }}">{{ trans('common.info')}}</a>
                        </div>
                    </div>
                    @endif
                    <div id="show-consumer-info" class="form-group row" style="display:none">
                        <div class="col-sm-offset-4 col-sm-8">
                            <a id="js-show-consumer-info" class="js-show-consumer-info btn btn-primary" href="{{ route('bookings.consumer_info') }}" data-consumerid="{{ !empty($booking->consumer->id) ? $booking->consumer->id : '' }}">{{ trans('common.info')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- endrow -->
        </div>
    </div>
</div>  