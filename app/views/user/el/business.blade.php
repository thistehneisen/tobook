@if ($businessLomake) {{ $businessLomake->open(['id' => 'business-form']) }}
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

    @if (Entrust::hasRole(App\Core\Models\Role::ADMIN) || Session::has('stealthMode'))
    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.business.is_hidden') }}</label>
        <div class="col-sm-6">
            <div class="radio">
                <label>{{ Form::radio('is_hidden', 0, $business->is_hidden === false) }} {{ trans('common.no') }}</label>
            </div>
            <div class="radio">
                <label>{{ Form::radio('is_hidden', 1, $business->is_hidden === true) }} {{ trans('common.yes') }}</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.business.note') }}</label>
        <div class="col-sm-6">
            {{ Form::textarea('note', Input::get('note', $business->note), ['class' => 'form-control']) }}
        </div>
    </div>

    @foreach (['meta_title', 'meta_description', 'meta_keywords', 'bank_account'] as $field)
    <div class="form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.business.'.$field) }}</label>
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $business->$field), ['class' => 'form-control']) }}
        </div>
    </div>
    @endforeach
    @endif

    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
            <input type="hidden" name="tab" value="business">
            <a target="_blank" href="{{ $business->business_url }}" class="btn btn-default btn-lg comfortaa">{{ trans('user.business.preview') }}</a>
            <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa pull-right">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ $businessLomake->close() }}
@endif
