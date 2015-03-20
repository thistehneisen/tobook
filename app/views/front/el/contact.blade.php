<h3 class="sub-heading">{{ trans('home.business.contact.heading') }}</h3>

{{ Form::open(['route' => ['business.contact', $business->user_id, $business->slug], 'class' => 'form-vertical']) }}
    <div class="form-group">
        {{ Form::label('name', trans('home.business.contact.name')) }}
        {{ Form::text('name', Input::get('name'), ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('email', trans('home.business.contact.email')) }}
        {{ Form::text('email', Input::get('email'), ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('phone', trans('home.business.contact.phone')) }}
        {{ Form::text('phone', Input::get('phone'), ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('captcha', trans('home.business.contact.captcha')) }}
        <p><img src="{{ Captcha::img() }}" alt=""></p>
        {{ Form::text('captcha', '', ['class' => 'form-control']) }}
    </div>
    <div class="form-group">
        {{ Form::label('message', trans('home.business.contact.message')) }}
        {{ Form::textarea('message', Input::get('message'), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.submit') }}</button>
    </div>
{{ Form::close() }}
