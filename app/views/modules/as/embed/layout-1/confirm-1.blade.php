<div class="list-group">
     @if(!empty($consumer))
     <div class="alert alert-warning">
        <p>{{ trans('as.bookings.warning.existing_user')}}</p>
     </div>
    <div class="alert alert-info">
        <div class="form-group row">
            <div class="col-sm-2">{{ trans('as.bookings.firstname') }} (*)</div>
            <div class="col-sm-10"><span id="existing_firstname">{{ $consumer->first_name }}</span></div>
        </div>
         <div class="form-group row">
            <div class="col-sm-2">{{ trans('as.bookings.lastname') }}</div>
            <div class="col-sm-10"><span id="existing_lastname">{{ $consumer->last_name }}</span></div>
        </div>
         <div class="form-group row">
            <div class="col-sm-2">{{ trans('as.bookings.email') }}</div>
            <div class="col-sm-10"><span id="existing_email">{{ $consumer->email }}</span></div>
        </div>
         <div class="form-group row">
            <div class="col-sm-2">{{ trans('as.bookings.phone') }}</div>
            <div class="col-sm-10"><span id="existing_phone">{{ $consumer->phone }}</span></div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12"><a href="" id="btn-select-existing-user" class="btn btn-default btn-success">{{ trans('common.select') }}</a></div>
        </div>
    </div>
    <br>
     @endif
    <div class="list-group-item">
        <form id="form-confirm-booking">
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.firstname') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_firstname">{{ (isset($booking_info['firstname'])) ? $booking_info['firstname'] : trans('as.bookings.empty') }}</span>
                    {{ Form::hidden('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] :'', ['class' => 'form-control input-sm', 'id' => 'firstname']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.lastname') }}</div>
                <div class="col-sm-10">
                    <span id="display_lastname">{{(isset($booking_info['lastname'])) ? $booking_info['lastname'] : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('lastname', (isset($booking_info['lastname'])) ? $booking_info['lastname'] :'', ['class' => 'form-control input-sm', 'id' => 'lastname']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.email') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_email">{{ (isset($booking_info['email'])) ? $booking_info['email'] : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('email', (isset($booking_info['email'])) ? $booking_info['email']:'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.phone') }} (*)</div>
                <div class="col-sm-10">
                    <span id="display_phone">{{ (isset($booking_info['phone'])) ? $booking_info['phone'] : trans('as.bookings.empty')  }}</span>
                    {{ Form::hidden('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] :'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                </div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
        </form>
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><a href="{{ route('as.embed.embed', ['hash' => $hash, 'action' => 'checkout']) }}" class="btn btn-default">{{ trans('common.cancel') }}</a></div>
        <div class="col-sm-6">
            <button data-success-msg="Varauksesi on vahvistettu! Kiitos varauksestasi." data-success-url="{{ route('as.embed.embed', ['hash'=> $hash]) }}" data-action-url="{{ route('as.bookings.frontend.add') }}" id="btn-confirm-booking" class="btn btn-success pull-right">{{ trans('as.bookings.confirm_booking') }}</button>
        </div>
    </div>
</div>
