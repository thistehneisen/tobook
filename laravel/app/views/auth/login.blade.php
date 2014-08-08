@extends ('layouts.default')

@section ('title')
    Kirjaudu :: @parent
@stop

@section ('header')
    <h1 class="text-header">Kirjaudu</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="comfortaa orange">Kirjaudu sisään</h1>
        <h4 class="comfortaa">Täytä seuraavat tiedot:</h4>

        @include ('el.messages');

        {{ Form::open(['route' => 'auth.login', 'class' => 'form-horizontal', 'role' => 'form']) }}
        
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
                <div class="col-sm-9 text-right">
                    Uusi käyttäjä? <a href="#">Rekisteröidy tästä</a> | Unohditko salasanasi? <a href="">Klikkaa tästä.</a>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-9 text-right">
                    <button id="btn-login" class="btn btn-lg btn-orange to-upper comfortaa">Kirjaudu</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
