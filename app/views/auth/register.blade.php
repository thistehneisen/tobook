@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('common.register') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('common.register') }}</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('user.create_account') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}:</h4>
        
        @include ('el.messages')

        {{ Form::open(['id' => 'frm-register', 'route' => 'auth.register', 'class' => 'form-horizontal', 'role' => 'form']) }}
        
        @foreach ($fields as $name => $field)
            <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
            <div class="form-group {{ Form::errorCSS($name, $errors) }}">
                {{ Form::label($name, $field['label'].Form::required($name, $validator), ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
                <div class="col-sm-6">
            @if ($type === 'password')
                {{ Form::$type($name, ['class' => 'form-control']) }}
            @else
                {{ Form::$type($name, Input::get($name), ['class' => 'form-control']) }}
            @endif
                {{ Form::errorText($name, $errors) }}
                </div>
            </div>
        @endforeach

            <div class="form-group">
                <div class="col-sm-9 col-sm-offset-3">
                    <p>{{ trans('user.accept_terms') }} <a href="#" id="link_terms">{{ trans('user.terms') }}</a></p>
                    <p>{{ trans('user.register_already')}} <a href="{{ route('auth.login') }}" title="" id="link-login">{{ trans('common.sign_in') }}</a></p>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 text-right">
                    <button class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.register') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
