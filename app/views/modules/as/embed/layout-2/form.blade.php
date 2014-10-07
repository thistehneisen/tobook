@if ($errors->any())
<div class="alert alert-danger">
    {{ implode('', $errors->all('<p>:message</p>')) }}
</div>
@endif

{{ Form::open(['route' => 'as.embed.l2.confirm', 'role' => 'form', 'id' => 'frm-customer-info']) }}
    <div class="form-group">
        <label>{{ trans('as.bookings.first_name') }}*</label>
        {{ Form::text('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'firstname']) }}
    </div>
    <div class="form-group">
        <label>{{ trans('as.bookings.last_name') }}*</label>
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

    <input type="hidden" name="hash" value="{{ Input::get('hash') }}">
    <input type="hidden" name="l" value="{{ Input::get('l') }}">

    <div class="row">
        <div class="col-sm-12">
            <a href="#" class="btn btn-default btn-as-cancel">{{ trans('as.embed.cancel') }}</a>
            <button type="submit" class="btn btn-success pull-right">{{ trans('as.embed.book') }}</button>
        </div>
    </div>
{{ Form::close() }}
