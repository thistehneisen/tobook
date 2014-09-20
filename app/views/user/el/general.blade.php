{{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>

@foreach (['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'postcode', 'country'] as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        {{ Form::label($field, trans('user.'.$field).Form::required($field, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $user->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
@endforeach

    <div class="form-group">
        <div class="col-sm-9 text-right">
            <input type="hidden" name="tab" value="general">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
