{{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>

@foreach (['business_name'] as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        {{ Form::label($field, trans('user.'.$field).Form::required($field, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $user->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
@endforeach

    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_categories.index') }}</label>
        <div class="col-sm-6">
            @include ('user.el.categories')
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.description') }}</label>
        <div class="col-sm-6">
            {{ Form::textarea('description', Input::get('description', $user->description), ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-group">
        <label for="business_size" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_size') }}</label>
        <div class="col-sm-6">
            {{ Form::select('business_size', trans('user.profile.business_size_values'), $user->business_size, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 text-right">
            <input type="hidden" name="tab" value="general">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
