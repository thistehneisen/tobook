@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.register') }}
@stop

@section('content')
<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-6">
        <h1 class="comfortaa orange text-center">{{ trans('user.create_account') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}</h4>

        @include ('el.messages')

        {{ $lomake->open() }}
            @foreach ($lomake->fields as $field)
                <div class="form-group row {{ Form::errorCSS($field->name, $errors) }}">
                    {{ Form::label($field->name, $field->label, ['class' => 'col-sm-3 control-label']) }}
                    <div class="col-sm-8">
                    {{ $field }}
                    <!-- Validation error -->
                    {{ Form::errorText($field->name, $errors) }}
                    </div>
                </div>
            @endforeach

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <p>{{ trans('user.accept_terms') }} <a href="#" id="link_terms">{{ trans('user.terms') }}</a></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    {{ Form::hidden('fromCheckout', Session::get('fromCheckout', Input::get('fromCheckout'))) }}
                    <button type="submit" id="btn-register" class="btn btn-lg btn-success text-uppercase comfortaa">
                        {{ trans('common.register') }}
                        <i class="fa fa-check-circle"></i>
                    </button>
                </div>
            </div>
        {{ $lomake->close() }}
    </div>

    <div class="col-sm-6 col-md-6 col-lg-6">
        <h1 class="comfortaa orange text-center">{{ trans('common.sign_in') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}</h4>

        {{ Form::open(['id' => 'frm-login', 'route' => 'auth.login', 'class' => 'form-horizontal well', 'role' => 'form']) }}

            <div class="form-group row">
                <div class="col-sm-9 col-sm-offset-3">
                    <p>{{ trans('user.register_already')}}</p>
                </div>
            </div>

            <div class="form-group row {{ Form::errorCSS('username', $errors) }}">
                {{ Form::label('username', trans('user.username').'*', ['class' => 'col-sm-3 control-label']) }}
                <div class="col-sm-8">
                {{ Form::text('username', Input::get('username'), ['class' => 'form-control']) }}
                <!-- Validation error -->
                {{ Form::errorText('username', $errors) }}
                </div>
            </div>


            <div class="form-group row {{ Form::errorCSS('password', $errors) }}">
                {{ Form::label('password', trans('user.password').'*', ['class' => 'col-sm-3 control-label']) }}
                <div class="col-sm-8">
                {{ Form::password('password', ['class' => 'form-control']) }}
                <!-- Validation error -->
                {{ Form::errorText('password', $errors) }}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <button class="btn btn-lg btn-success text-uppercase comfortaa">{{ trans('common.sign_in') }} <i class="fa fa-sign-in"></i></button>
                </div>
            </div>

        {{ Form::close() }}
    </div>
</div>
@stop
