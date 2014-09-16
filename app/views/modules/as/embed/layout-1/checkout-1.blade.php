<div class="list-group">
    @if ($errors->any())
    <div class="alert alert-warning">
        <ul>
            {{ implode('', $errors->all('<li>:message</li>')) }}
        </ul>
    </div>
    @endif
    <div class="list-group-item">
        <form id="form-confirm-booking" action="{{ route('as.embed.confirm') }}" method="POST">
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.firstname') }} (*)</div>
                <div class="col-sm-10"> {{ Form::text('firstname', (isset($booking_info['firstname'])) ? $booking_info['firstname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'firstname']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.lastname') }}</div>
                <div class="col-sm-10"> {{ Form::text('lastname', (isset($booking_info['lastname'])) ? $booking_info['lastname'] : ''  , ['class' => 'form-control input-sm', 'id' => 'lastname']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.email') }} (*)</div>
                <div class="col-sm-10">{{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.phone') }} (*)</div>
                <div class="col-sm-10">{{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}</div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
            @if((int)$user->asOptions['terms_enabled'] > 1)
            <div class="form-group row">
                <div class="col-sm-offset-2 col-sm-6 {{ Form::errorCSS('is_day_off', $errors) }}">
                    @if((int)$user->asOptions['terms_enabled'] == 3)
                    <label>{{ Form::checkbox('terms', 0, 0,['id'=>'terms']); }} <a href="#" id="toggle_term">{{ trans('as.bookings.terms') }}</a></label>
                    @else
                    <label><a href="#" id="toggle_term">{{ trans('as.bookings.terms') }}</a></label>
                    @endif
                </div>
             </div>
            <div class="form-group row" id="terms_body" style="display:none">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                {{ nl2br($user->asOptions['terms_body']) }}
                </div>
             </div>
            @endif
    </div>
    <br>
    <div class="form-group row">
        <div class="col-sm-6"><a href="{{ route('as.embed.embed', ['hash' => $hash]) }}" class="btn btn-default">{{ trans('common.cancel') }}</a></div>
        <div class="col-sm-6">
            <button type="submit" id="btn-checkout-submit" data-term-error-msg="{{ trans('as.bookings.error.terms')}}" data-term-enabled="{{ $user->asOptions['terms_enabled'] }}" class="btn btn-success pull-right">{{ trans('common.continue') }}</button>
        </div>
    </div>
    </form>
</div>
