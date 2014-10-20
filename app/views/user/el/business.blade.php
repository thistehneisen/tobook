{{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
    <h3 class="comfortaa orange">{{ trans('user.profile.business') }}</h3>

    @foreach ($businessLomake->fields as $field)
        @include('varaa-lomake::fields.group')
    @endforeach

    <div class="form-group {{ Form::errorCSS('size', $errors) }}">
        <label for="size" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.business.size') }}</label>
        <div class="col-sm-6">
            {{ Form::select('size', trans('user.business.sizes'), $business->size, ['class' => 'form-control']) }}
            {{ Form::errorText('size', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_categories.index') }}</label>
        <div class="col-sm-6">
            @include ('user.el.categories')
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-9 text-right">
            <input type="hidden" name="tab" value="business">
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
