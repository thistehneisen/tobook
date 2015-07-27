@if ($errors->getBag('errors')->any())
<div class="alert alert-danger">
    {{ implode('', $errors->getBag('errors')->all('<p>:message</p>')) }}
</div>
@endif

{{ Form::open(['route' => 'as.bookings.frontend.add', 'role' => 'form', 'id' => 'as-form-checkout', 'data-success-url' => route('as.embed.embed', ['hash'=> Input::get('hash'), 'l' => Input::get('l')]), 'data-term-enabled'=> $user->asOptions['terms_enabled'], 'data-term-error-msg' => trans('as.bookings.error.terms') ]) }}
    <div class="form-group">
        <label>{{ trans('as.bookings.first_name') }}*</label>
        {{ Form::text('first_name', (isset($booking_info['first_name'])) ? $booking_info['first_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'first_name']) }}
    </div>
    <div class="form-group">
        <label>{{ trans('as.bookings.last_name') }}*</label>
        {{ Form::text('last_name', (isset($booking_info['last_name'])) ? $booking_info['last_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'last_name']) }}
    </div>

    @if((int)$user->asOptions['email'] >= 2)
    <div class="form-group">
        <label>{{ trans('as.bookings.email') }}@if((int)$user->asOptions['email'] === 3)*@endif</label>
        {{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}
    </div>
    @endif
    <div class="form-group">
        <label>{{ trans('as.bookings.phone') }}*</label>
        {{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}
    </div>
    <?php $fields = ['notes', 'address', 'city', 'postcode']; ?>
    @foreach($fields as $field)
        @if((int)$user->asOptions[$field] >= 2)
        <div class="form-group">
           <label>{{ trans("as.bookings.$field") }} @if((int)$user->asOptions[$field] === 3)*@endif</label>
           {{ Form::text($field, (isset($booking_info[$field])) ? $booking_info[$field] : ''  , ['class' => 'form-control input-sm', 'id' => $field]) }}
        </div>
        @endif
    @endforeach

    @if((int)$user->asOptions['country'] >= 2)
    <div class="form-group">
         <label>{{ trans('as.bookings.country') }}  @if((int)$user->asOptions['country'] === 3)(*)@endif</label>
        {{ Form::select('country', array_combine($user->business->getCountryList(), $user->business->getCountryList()), (isset($booking_info['country'])) ? $booking_info['country'] : '', ['class' => 'form-control input-sm', 'id' => 'country']) }}
        </div>
    </div>
    @endif
    @if((bool)$user->asOptions['show_employee_request'])
    <div class="form-group">
        <div class="checkbox">
            <label for="is_requested_employee">{{ Form::checkbox('is_requested_employee', 1, (isset($booking_info['is_requested_employee'])) ? $booking_info['is_requested_employee'] : '', ['id' => 'is_requested_employee']) }} {{ trans('as.bookings.request_employee') }}</label>
        </div>
    </div>
    @endif
    <input type="hidden" name="hash" value="{{ Input::get('hash') }}">
    <input type="hidden" name="l" value="{{ Input::get('l') }}">
    <input type="hidden" name="service_id" value="{{ Input::get('serviceId') }}">
    <input type="hidden" name="service_time_id" value="{{ Input::get('serviceTimeId') }}">
    <input type="hidden" name="employee_id" value="{{ Input::get('employeeId') }}">
    <input type="hidden" name="date" value="{{ Input::get('date') }}">
    <input type="hidden" name="time" value="{{ Input::get('time') }}">
    <input type="hidden" name="cart_id" value="{{ Input::get('cartId') }}">
    <input type="hidden" name="inhouse" value="{{ Input::get('inhouse') }}">
    <input type="hidden" name="source" value="{{ Input::get('src', 'layout3') }}">

    @if ((int) $user->asOptions['terms_enabled'] > 1)
        <?php
        $terms_class = empty($user->asOptions['terms_url'])
            ? 'href="#" id="toggle_term"'
            : 'href="'.$user->asOptions['terms_url'].'" target="_blank"';
        ?>
        <div class="form-group">
            <div class="checkbox {{ Form::errorCSS('is_day_off', $errors) }}">
                @if ((int) $user->asOptions['terms_enabled'] === 3)
                <label>{{ Form::checkbox('terms', 0, 0,['id'=>'terms']); }} <a {{ $terms_class }}>{{ trans('as.bookings.terms_agree') }}</a></label>
                @else
                <label><a {{ $terms_class }}>{{ trans('as.bookings.terms') }}</a></label>
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

    <div class="form-group">
        <button type="submit" id="btn-checkout-submit" class="btn btn-success">{{ trans('as.embed.book') }}</button>
        <span class="text-success"></span>
        <span class="as-loading">
            <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
        </span>
    </div>
{{ Form::close() }}
