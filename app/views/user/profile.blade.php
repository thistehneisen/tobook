@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('user.change_profile') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('user.change_profile') }}</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li {{ $activeTab === 'general' ? 'class="active"' : '' }}><a href="#general" role="tab" data-toggle="tab">{{ trans('user.profile.general') }}</a></li>
            <li {{ $activeTab === 'password' ? 'class="active"' : '' }}><a href="#password" role="tab" data-toggle="tab">{{ trans('user.change_password') }}</a></li>
            <li {{ $activeTab === 'images' ? 'class="active"' : '' }}><a href="#images" role="tab" data-toggle="tab">{{ trans('user.profile.images') }}</a></li>
        </ul>
        <br>
        @include ('el.messages')

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane" id="general">
            {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <h3 class="comfortaa orange">{{ trans('user.profile.general') }}</h3>
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
            </div> <!-- General information -->

            <div class="tab-pane {{ $activeTab === 'password' ? 'active' : '' }}" id="password">
            {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
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
                        <input type="hidden" name="tab" value="password">
                        <button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
            </div> <!-- Change password -->

            <div class="tab-pane active" id="images">
            {{ Form::open(['id' => 'frm-profile', 'route' => 'user.profile', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <h3 class="comfortaa orange">{{ trans('user.profile.images') }}</h3>

                @include ('el.uploader')
            {{ Form::close() }}
            </div> <!-- Images -->
        </div>
    </div>
</div>
@stop
