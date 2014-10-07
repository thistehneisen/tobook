{{ Form::open(['route' => 'as.embed.checkout.confirm', 'role' => 'form','id' => 'as-confirm']) }}
    <div class="form-group">
        <label>{{ trans('as.bookings.firstname') }}*</label>
        {{ Form::text('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'firstname']) }}
    </div>
    <div class="form-group">
        <label>{{ trans('as.bookings.lastname') }}</label>
        {{ Form::text('lastname', (isset($booking_info['lastname'])) ? $booking_info['lastname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'lastname']) }}
    </div>
    <div class="form-group">
        <label>{{ trans('as.bookings.email') }}*</label>
        {{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}
    </div>
    <div class="form-group">
        <label>{{ trans('as.bookings.phone') }}*</label>
        {{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}
    </div>
{{ Form::close() }}
