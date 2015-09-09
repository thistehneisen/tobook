{{ Form::open(['id' => 'general-form', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>

    <div class="form-group {{ Form::errorCSS('email', $errors) }}">
        {{ Form::label('email', trans('user.email').Form::required('email', $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('email', Input::get('email', $user->email), ['class' => 'form-control']) }}
            {{ Form::errorText('email', $errors) }}
        </div>
    </div>

    <div class="form-group {{ Form::errorCSS('business_id', $errors) }}">
        {{ Form::label('business_id', trans('user.business_id').Form::required('business_id', $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('business_id', Input::get('business_id', $user->business_id), ['class' => 'form-control']) }}
            {{ Form::errorText('business_id', $errors) }}
        </div>
    </div>

    <div class="form-group {{ Form::errorCSS('account', $errors) }}">
        {{ Form::label('account', trans('user.account').Form::required('account', $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('account', Input::get('account', $user->account), ['class' => 'form-control']) }}
            {{ Form::errorText('account', $errors) }}
        </div>
    </div>
@if (Confide::user()->is_admin || Session::has('stealthMode'))
    <div class="form-group">
        {{ Form::label('payment_options', trans('user.payment_options.index'), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            <div class="checkbox">
                <label>{{ Form::checkbox('payment_options[]', 'venue', $business->isPaymentOptionEnabled('venue')) }} @lang('user.payment_options.venue')</label>
            </div>
            @if((bool) Settings::get('deposit_payment') && (App::environment() === 'tobook'))
            <div class="checkbox">
                <label>{{ Form::checkbox('payment_options[]', 'deposit', $business->isPaymentOptionEnabled('deposit'), ['id' => 'js-payment-options-deposit']) }} @lang('user.payment_options.deposit')</label>
            </div>
            @endif
            <div class="checkbox">
                <label>{{ Form::checkbox('payment_options[]', 'full', $business->isPaymentOptionEnabled('full')) }} @lang('user.payment_options.full')</label>
            </div>
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('deposit_rate', $errors) }} soft-hidden" id="js-deposit-rate">
        {{ Form::label('deposit_rate', trans('user.payment_options.rate'), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('deposit_rate', Input::get('deposit_rate', $business->deposit_rate), ['class' => 'form-control', 'placeholder' => trans('user.payment_options.placeholder')]) }}
            {{ Form::errorText('deposit_rate', $errors) }}
        </div>
    </div>
    {{-- layout-cp settings --}}
    <div class="form-group">
        {{ Form::label('payment_options', trans('user.payment_options.disable'), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            <div class="radio">
                <label>{{ Form::radio('disabled_payment', 1, $business->disabled_payment === true) }} @lang('common.yes')</label>
            </div>

            <div class="radio">
                <label>{{ Form::radio('disabled_payment', 0, $business->disabled_payment === false) }} @lang('common.no')</label>
            </div>
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('deposit_rate', $errors) }} soft-hidden" id="js-deposit-rate">
        {{ Form::label('deposit_rate', trans('user.payment_options.rate'), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text('deposit_rate', Input::get('deposit_rate', $business->deposit_rate), ['class' => 'form-control', 'placeholder' => trans('user.payment_options.placeholder')]) }}
            {{ Form::errorText('deposit_rate', $errors) }}
        </div>
    </div>
@endif

@if ($consumer)
@foreach (['first_name', 'last_name', 'phone', 'address', 'city', 'postcode', 'country'] as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        {{ Form::label($field, trans('user.'.$field).Form::required($field, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $consumer->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
@endforeach
@endif

    <div class="form-group">
        <div class="col-sm-9 text-right">
            <input type="hidden" name="tab" value="general">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
