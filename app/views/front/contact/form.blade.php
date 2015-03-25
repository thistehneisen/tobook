@include ('el.messages')

{{ Form::open([
    'route' => ['business.contact', $business->user_id, $business->slug],
    'class' => 'form-vertical',
    'id'    => 'form-contact-business',
    'style' => Input::get('src', 'contact') === 'contact' ? '' : 'display: none;']) }}

    <h3 class="sub-heading">{{ trans('home.business.contact.heading') }}</h3>

    <div class="form-group {{ Form::errorCSS('contact_name', $errors) }}">
        {{ Form::label('contact_name', trans('home.business.contact.name')) }}
        {{ Form::text('contact_name', Input::get('contact_name'), ['class' => 'form-control']) }}
        {{ Form::errorText('contact_name', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('contact_email', $errors) }}">
        {{ Form::label('contact_email', trans('home.business.contact.email')) }}
        {{ Form::text('contact_email', Input::get('contact_email'), ['class' => 'form-control']) }}
        {{ Form::errorText('contact_email', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('contact_phone', $errors) }}">
        {{ Form::label('contact_phone', trans('home.business.contact.phone')) }}
        {{ Form::text('contact_phone', Input::get('contact_phone'), ['class' => 'form-control']) }}
        {{ Form::errorText('contact_phone', $errors) }}
    </div>
{{--
    <div class="form-group {{ Form::errorCSS('contact_captcha', $errors) }}">
        {{ Form::label('contact_captcha', trans('home.business.contact.captcha')) }}
        <p><img src="{{ Captcha::img() }}" alt=""></p>
        {{ Form::text('contact_captcha', '', ['class' => 'form-control']) }}
        {{ Form::errorText('contact_captcha', $errors) }}
    </div>
--}}
    <div class="form-group {{ Form::errorCSS('contact_message', $errors) }}">
        {{ Form::label('contact_message', trans('home.business.contact.message')) }}
        {{ Form::textarea('contact_message', Input::get('contact_message'), ['class' => 'form-control']) }}
        {{ Form::errorText('contact_message', $errors) }}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
        <a href="#" id="js-business-booking-request" class="btn btn-success pull-right">{{ trans('home.business.request.link') }}</a>
    </div>
{{ Form::close() }}

{{ Form::open([
    'route' => ['business.request', $business->user_id, $business->slug],
    'id'    => 'form-request-business',
    'style' => Input::get('src', 'contact') === 'request' ? '' : 'display: none;']) }}
    <h3 class="sub-heading">{{ trans('home.business.request.link') }}</h3>

    <div class="alert alert-info"><p>{{ trans('home.business.request.info') }}</p></div>
    <div class="form-group {{ Form::errorCSS('request_name', $errors) }}">
        {{ Form::label('request_name', trans('home.business.contact.name')) }}
        {{ Form::text('request_name', Input::get('request_name'), ['class' => 'form-control']) }}
        {{ Form::errorText('request_name', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('request_email', $errors) }}">
        {{ Form::label('request_email', trans('home.business.contact.email')) }}
        {{ Form::text('request_email', Input::get('request_email'), ['class' => 'form-control']) }}
        {{ Form::errorText('request_email', $errors) }}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('common.submit') }}</button>
        <a href="#" id="js-cancel-business-request" class="btn btn-link">{{ trans('common.cancel') }}</a>
    </div>
{{ Form::close() }}
