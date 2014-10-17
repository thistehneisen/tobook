@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.register') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('user.create_account') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}</h4>

        @include ('el.messages')

        {{ $lomake->open() }}
            @foreach ($lomake->fields as $field)
                <div class="form-group {{ Form::errorCSS($field->name, $errors) }}">
                    {{ Form::label($field->name, $field->label, ['class' => 'col-sm-2 col-sm-offset-1 control-label']) }}
                    <div class="col-sm-6">
                    {{ $field }}
                    <!-- Validation error -->
                    {{ Form::errorText($field->name, $errors) }}
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
                    {{ Form::hidden('fromCheckout', Session::get('fromCheckout', Input::get('fromCheckout'))) }}
                    <button class="btn btn-lg btn-success text-uppercase comfortaa">{{ trans('common.register') }} <i class="fa fa-check-circle"></i></button>
                </div>
            </div>
        {{ $lomake->close() }}
    </div>
</div>
@stop
