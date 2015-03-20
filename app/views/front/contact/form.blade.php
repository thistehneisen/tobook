<h3 class="sub-heading">{{ trans('home.business.contact.heading') }}</h3>

@include ('el.messages')

{{ Form::open(['route' => ['business.contact', $business->user_id, $business->slug], 'class' => 'form-vertical']) }}

    <div class="form-group {{ Form::errorCSS('name', $errors) }}">
        {{ Form::label('name', trans('home.business.contact.name')) }}
        {{ Form::text('name', Input::get('name'), ['class' => 'form-control']) }}
        {{ Form::errorText('name', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('email', $errors) }}">
        {{ Form::label('email', trans('home.business.contact.email')) }}
        {{ Form::text('email', Input::get('email'), ['class' => 'form-control']) }}
        {{ Form::errorText('email', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('phone', $errors) }}">
        {{ Form::label('phone', trans('home.business.contact.phone')) }}
        {{ Form::text('phone', Input::get('phone'), ['class' => 'form-control']) }}
        {{ Form::errorText('phone', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('captcha', $errors) }}">
        {{ Form::label('captcha', trans('home.business.contact.captcha')) }}
        <p><img src="{{ Captcha::img() }}" alt=""></p>
        {{ Form::text('captcha', '', ['class' => 'form-control']) }}
        {{ Form::errorText('captcha', $errors) }}
    </div>

    <div class="form-group {{ Form::errorCSS('message', $errors) }}">
        {{ Form::label('message', trans('home.business.contact.message')) }}
        {{ Form::textarea('message', Input::get('message'), ['class' => 'form-control']) }}
        {{ Form::errorText('message', $errors) }}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.submit') }}</button>
    </div>
{{ Form::close() }}
