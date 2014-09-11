@extends ('layouts.default')

@section ('title')
    NFC Desktop App
@stop

@section('logo')
<h1 class="text-header">{{ trans('dashboard.loyalty') }}</h1>
@stop

@section('nav')
<nav class="row">
    <div class="col-md-6 text-left">
        <i class="fa fa-globe"></i>
        @foreach (Config::get('varaa.languages') as $locale)
        <a class="language-swicher {{ Config::get('app.locale') === $locale ? 'active' : '' }}" href="{{ UrlHelper::localizedCurrentUrl($locale) }}" title="">{{ strtoupper($locale) }}</a>
        @endforeach
    </div>
    <div class="col-md-6 text-right">
        <ul class="list-inline nav-links">
            @if (Confide::user())
            <li><a href="{{ route('auth.appLogout') }}">{{ trans('common.sign_out') }}</a></li>
            @endif
        </ul>
    </div>
</nav>
@stop

@section('footer')
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange text-center">{{ trans('common.sign_in') }}</h1>
        <h4 class="comfortaa text-center">{{ trans('user.fill_fields') }}</h4>

        @include ('el.messages')

        {{ Form::open(['id' => 'frm-login', 'route' => 'app.lc.login', 'class' => 'form-horizontal', 'role' => 'form']) }}

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
                    <button id="btn-login" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.sign_in') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
