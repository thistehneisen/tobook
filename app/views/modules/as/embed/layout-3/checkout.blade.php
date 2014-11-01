@if ($errors->any())
<div class="alert alert-danger">
    {{ implode('', $errors->all('<p>:message</p>')) }}
</div>
@endif

{{ Form::open(['route' => 'as.embed.checkout.confirm', 'role' => 'form', 'class' => 'form-horizontal', 'id' => 'as-confirm']) }}
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.first_name') }}*</label>
        <div class="col-sm-10"> {{ Form::text('first_name', (isset($booking_info['first_name'])) ? $booking_info['first_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'first_name']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.last_name') }}*</label>
        <div class="col-sm-10"> {{ Form::text('last_name', (isset($booking_info['last_name'])) ? $booking_info['last_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'last_name']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.email') }}*</label>
        <div class="col-sm-10">{{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}</div>
    </div>
    <div class="form-group">
        <label class="form-label col-sm-2">{{ trans('as.bookings.phone') }}*</label>
        <div class="col-sm-10">{{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}</div>
    </div>
    <?php $fields = ['notes', 'address', 'city', 'postcode']; ?>
    @foreach($fields as $field)
        @if((int)$user->asOptions[$field] >= 2)
        <div class="form-group">
           <label class="form-label col-sm-2">{{ trans("as.bookings.$field") }} @if((int)$user->asOptions[$field] === 3)*@endif</label>
           <div class="col-sm-10">{{ Form::text($field, (isset($booking_info[$field])) ? $booking_info[$field] : ''  , ['class' => 'form-control input-sm', 'id' => $field]) }}</div>
        </div>
        @endif
    @endforeach

    @if((int)$user->asOptions['country'] >= 2)
    <div class="form-group">
         <label class="form-label col-sm-2">{{ trans('as.bookings.country') }}  @if((int)$user->asOptions['country'] === 3)(*)@endif</label>
        <div class="col-sm-10">{{ Form::select('country', array_combine($user->business->getCountryList(), $user->business->getCountryList()), (isset($booking_info['country'])) ? $booking_info['country'] : '', ['class' => 'form-control input-sm', 'id' => 'country']) }}</div>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
          <label for="is_requested_employee">{{ Form::checkbox('is_requested_employee', 1, (isset($booking_info['is_requested_employee'])) ? $booking_info['is_requested_employee'] : '', ['id' => 'is_requested_employee']) }} {{ trans('as.bookings.request_employee') }}</label>
        </div>
    </div>

    <input type="hidden" name="hash" value="{{ Input::get('hash') }}">
    <input type="hidden" name="l" value="{{ Input::get('l') }}">
    <input type="hidden" name="serviceId" value="{{ Input::get('serviceId') }}">
    <input type="hidden" name="employeeId" value="{{ Input::get('employeeId') }}">
    <input type="hidden" name="date" value="{{ Input::get('date') }}">
    <input type="hidden" name="time" value="{{ Input::get('time') }}">
    <input type="hidden" name="cartId" value="{{ Input::get('cartId') }}">
    <input type="hidden" name="inhouse" value="{{ Input::get('inhouse') }}">

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
                <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
            </span>
        </div>
    </div>
{{ Form::close() }}
