@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('user.change_password') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('user.change_password') }}</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        @include ('el.messages')
        {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}

        <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>
        <div class="form-group">
            <label class="col-sm-2 col-sm-offset-1 control-label">{{ trans('user.profile.business_categories.index') }}</label>
            <div class="col-sm-6">
                @foreach ($categories as $category)
                <div class="checkbox">
                    <label>
                        {{ Form::checkbox('categories[]', $category->id, in_array($category->id, $selectedCategories)) }} {{ trans('user.profile.business_categories.'.$category->name) }}
                    </label>
                </div>
                @endforeach
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

        <h3 class="comfortaa orange">{{ trans('user.change_password') }}</h3>
        @foreach ($fields as $name => $field)
            <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
            <div class="form-group {{ Form::errorCSS($name, $errors) }}">
                {{ Form::label($name, $field['label'].Form::required($name, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
                <div class="col-sm-6">
            @if ($type === 'password') {{ Form::$type($name, ['class' => 'form-control']) }}
            @else {{ Form::$type($name, Input::get($name), ['class' => 'form-control']) }}
            @endif
                {{ Form::errorText($name, $errors) }}
                </div>
            </div>
        @endforeach

            <div class="form-group">
                <div class="col-sm-9 text-right">
                    <button id="btn-login" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
