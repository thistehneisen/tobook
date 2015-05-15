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
