@if ($errors->any())
<div class="alert alert-warning">
    <ul>
        {{ implode('', $errors->all('<li>:message</li>')) }}
    </ul>
</div>
@endif

{{ Form::open(['route' => 'as.embed.checkout.confirm', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'as-confirm']) }}
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.firstname') }}*</label>
        <div class="col-sm-10"> {{ Form::text('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'firstname']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.lastname') }}</label>
        <div class="col-sm-10"> {{ Form::text('lastname', (isset($booking_info['lastname'])) ? $booking_info['lastname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'lastname']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.email') }}*</label>
        <div class="col-sm-10">{{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.phone') }}*</label>
        <div class="col-sm-10">{{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}</div>
    </div>

    <input type="hidden" name="hash" value="{{ Input::get('hash') }}">
    <input type="hidden" name="l" value="{{ Input::get('l') }}">

    @if ((int) $user->asOptions['terms_enabled'] > 1)
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6 {{ Form::errorCSS('is_day_off', $errors) }}">
            @if ((int) $user->asOptions['terms_enabled'] === 3)
            <label>{{ Form::checkbox('terms', 0, 0,['id'=>'terms']); }} <a href="#" id="toggle_term">{{ trans('as.bookings.terms_agree') }}</a></label>
            @else
            <label><a href="#" id="toggle_term">{{ trans('as.bookings.terms') }}</a></label>
            @endif
        </div>
    </div>
    <div class="form-group" id="terms_body" style="display:none">
        <div class="col-sm-2">&nbsp;</div>
        <div class="col-sm-10">
        {{ nl2br($user->asOptions['terms_body']) }}
        </div>
    </div>
    @endif
    <br>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" id="btn-checkout-submit" class="btn btn-success">{{ trans('common.continue') }}</button>
            <span class="as-loading">
                <i class="glyphicon glyphicon-refresh text-info"></i> Now loading&hellip;
            </span>
        </div>
    </div>
{{ Form::close() }}
