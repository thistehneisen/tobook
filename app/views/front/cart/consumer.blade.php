@extends ('layouts.default')

@section('title')
    {{ trans('home.cart.checkout') }}
@stop

@section('content')
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <h1 class="comfortaa orange text-center">@lang('as.embed.booking_form')</h1>

        {{ Form::open(['route' => 'cart.consumer', 'class' => 'form-horizontal well']) }}
        @foreach ($fields as $key => $opts)
            <div class="form-group row {{ Form::errorCSS($key, $errors) }}">
                {{ Form::label($key, trans('as.bookings.'.$key).($opts['required'] ? '*' : ''), ['class' => 'col-sm-3 control-label']) }}
                <div class="col-sm-8">
                {{ Form::text($key, Input::get($key), ['class' => 'form-control']) }}
                {{ Form::errorText($key, $errors) }}
                </div>
            </div>
        @endforeach

        @if ($showTerms)
            <div class="form-group row">
                <div class="col-sm-offset-3 col-sm-8">
                    <div class="checkbox">
                        <label><input type="checkbox"> @lang('as.bookings.terms_agree')</label>
                    </div>
                </div>
            </div>
        @endif

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-8">
                    <button class="btn btn-lg btn-success text-uppercase comfortaa">{{ trans('as.bookings.confirm_booking') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop
