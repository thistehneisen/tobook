@extends ('layouts.default')

@include('el.multimeta')

@section('title')
    {{ trans('common.sign_in_header') }}
@stop

@section('page-header')
    <h1 class="text-header">{{ trans('common.sign_in_header') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('common.sign_in_header') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}</h4>

        @include ('el.messages')

        {{ Form::open(['id' => 'frm-login', 'route' => 'auth.login', 'class' => 'form-horizontal', 'role' => 'form']) }}

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
                    {{ trans('user.new_customers') }}? <a href="{{ route('auth.register') }}" title="" id="link-register">{{ trans('user.register_here') }}</a> | {{ trans('user.forgot_password') }} <a href="{{ route('auth.forgot') }}" title="" id="link-forgot">{{ trans('user.click_here') }}.</a>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 text-right">
                    <button type="submit" id="btn-login" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.sign_in') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
