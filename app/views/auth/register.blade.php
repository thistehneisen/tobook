@extends ('layouts.front')

@section('title')
    {{ trans('common.register') }}
@stop

@section('css')
{{ HTML::style(asset_path('core/styles/register.css')) }}
@stop

@section('content')
 <div class="main">
    <div class="container">
        <div class="form-row">
            <div class="col-md-12 heading">
                <h1>Liity joukkoomme!</h1>
                <p>Saa uusia asiakkaita ja hallinoi olemassa olevia helpommin kun koskaan aiemmin</p>
            </div>
        </div>
        {{ Form::open(['id' => 'frm-register', 'route' => 'auth.register', 'class' => 'register-form form-horizontal', 'role' => 'form']) }}
            <div class="form-row">
                <div class="col-md-offset-1 col-md-6">
                    <div class="col-md-offset-2 col-md-10">
                        <h2>Rekisteröidy</h2>
                    </div>
                </div>
                <div class="col-md-5"><h2>Meistä</h2></div>
            </div>
            <div class="form-left col-md-offset-1 col-md-6">
                @include ('el.messages')

                @foreach ($fields as $name => $field)
                <?php $type = isset($field['type']) ? $field['type'] : 'text' ?>
                <div class="form-group {{ Form::errorCSS($name, $errors) }}">
                    {{ Form::label($name, $field['label'].Form::required($name, $validator), ['class' => 'col-sm-2 control-label']) }}
                    <div class="col-sm-10">
                        @if ($type === 'password') {{ Form::$type($name, ['class' => 'form-control']) }}
                        @else {{ Form::$type($name, Input::get($name), ['class' => 'form-control']) }}
                        @endif
                    </div>
                </div>
                @endforeach
                <div class="form-group">
                     <div class="col-md-offset-2 col-sm-10">
                     <button type="submit" id="btn-register" class="btn btn-md btn-orange" role="button">{{ trans('common.register') }}</button>
                     </div>
                </div>
            </div>
            <div class="form-right col-md-5">
                <div class="row guide">
                    <div class="col-md-2"><a href="#" class="btn btn-default btn-circle">1</a></div>
                    <div class="col-md-10 child-1">Suomen johtava ajanvarausportaali</div>
                </div>
                <div class="row guide">
                    <div class="col-md-2"><a href="#" class="btn btn-default btn-circle">2</a></div>
                    <div class="col-md-10 child-2">Varaa.com profiili parantaa yrityksesi näkyvyyttä verkossa, jotta asiakkaasi löytävät sinut entistäkin helpommin!</div>
                </div>
                <div class="row guide">
                   <div class="col-md-2"><a href="#" class="btn btn-default btn-circle">3</a></div>
                   <div class="col-md-10 child-3">Maksat vain Varaa.comin kautta tulleista varauksista. Ei ikäviä piilokuluja, ja maksat vain tuloksesta.</div>
                </div>
                <div class="row guide">
                    <div class="col-md-2"><a href="#" class="btn btn-default btn-circle">4</a></div>
                    <div class="col-md-10 child-4">Hallinnoi varauksia helpost sähköisen kalenterin avulla missä ja milloin haluat</div>
                </div>
            </div>
         {{ Form::close() }}
    </div>
</div>
@stop
