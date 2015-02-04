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
                <div class="col-sm-2">{{ trans('as.bookings.first_name') }} (*)</div>
                <div class="col-sm-10"> {{ Form::text('first_name', (isset($booking_info['first_name'])) ? $booking_info['first_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'first_name']) }}</div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.last_name') }}</div>
                <div class="col-sm-10"> {{ Form::text('last_name', (isset($booking_info['last_name'])) ? $booking_info['last_name'] : ''  , ['class' => 'form-control input-sm', 'id' => 'last_name']) }}</div>
            </div>
            @if((int)$user->asOptions['email'] >= 2)
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.email') }} @if((int)$user->asOptions['email'] === 3)(*)@endif</div>
                <div class="col-sm-10">{{ Form::text('email', (isset($booking_info['email'])) ? $booking_info['email'] : ''  , ['class' => 'form-control input-sm', 'id' => 'email']) }}</div>
            </div>
            @endif
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.phone') }} (*) </div>
                <div class="col-sm-10">{{ Form::text('phone', (isset($booking_info['phone'])) ? $booking_info['phone'] : ''  , ['class' => 'form-control input-sm', 'id' => 'phone']) }}</div>
            </div>

            <?php $fields = ['notes', 'address', 'city']; ?>
            @foreach($fields as $field)
                @if((int)$user->asOptions[$field] >= 2)
                <div class="form-group row">
                    <div class="col-sm-2">{{ trans("as.bookings.$field") }} @if((int)$user->asOptions[$field] === 3)(*)@endif</div>
                    <div class="col-sm-10">{{ Form::text($field, (isset($booking_info[$field])) ? $booking_info[$field] : ''  , ['class' => 'form-control input-sm', 'id' => $field]) }}</div>
                </div>
                @endif
            @endforeach

            @if((int)$user->asOptions['country'] >= 2)
            <div class="form-group row">
                <div class="col-sm-2">{{ trans('as.bookings.country') }}  @if((int)$user->asOptions['country'] === 3)(*)@endif</div>
                <div class="col-sm-10">
                {{ Form::select('country', array_combine($user->business->getCountryList(), $user->business->getCountryList()), (isset($booking_info['country'])) ? $booking_info['country'] : '', ['class' => 'form-control input-sm', 'id' => 'country']) }}
                </div>
            </div>
            @endif
            <div class="form-group row">
                <div class="col-sm-offset-2 col-sm-10">
                  <label for="is_requested_employee">{{ Form::checkbox('is_requested_employee', 1, (isset($booking_info['is_requested_employee'])) ? $booking_info['is_requested_employee'] : '', ['id' => 'is_requested_employee']) }} {{ trans('as.bookings.request_employee') }}</label>
                </div>
            </div>
            <input type="hidden" name="hash" value="{{ $hash }}">
            <input type="hidden" name="cart_id" value="{{ $cart->id }}">
            @if ((int)$user->asOptions['terms_enabled'] > 1)
                <?php
                $terms_class = empty($user->asOptions['terms_url'])
                    ? 'href="#" id="toggle_term"'
                    : 'href="'.$user->asOptions['terms_url'].'" target="_blank"';
                ?>
                <div class="form-group row">
                    <div class="col-sm-offset-2 col-sm-6 {{ Form::errorCSS('is_day_off', $errors) }}">
                        @if ((int)$user->asOptions['terms_enabled'] == 3)
                        <label>{{ Form::checkbox('terms', 0, 0,['id'=>'terms']); }} <a {{ $terms_class }}>{{ trans('as.bookings.terms_agree') }}</a></label>
                        @else
                        <label><a {{ $terms_class }}>{{ trans('as.bookings.terms') }}</a></label>
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
