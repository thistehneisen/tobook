@extends ('layouts.default')

@section('title')
    {{ trans('home.cart.checkout') }}
@stop

@section ('styles')
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style(asset('packages/alertify/css/themes/default.min.css')) }}
    <style>
        #js-terms {
            max-height: 400px;
            overflow: scroll;
        }
        .alertify .ajs-body .ajs-content {
            padding: 5px 0px;
        }
    </style>
@stop

@section ('scripts')
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script>
$(function () {
    $('#btn-confirm').on('click', function (e) {
        e.preventDefault();
        var dom = document.getElementById('js-terms');
        dom.style.display = 'block';
        alertify.confirm()
            .set('title', '{{ trans('common.notice') }}')
            .set('message', dom)
            .set('onok', function(){
                $('#confirm-consumer').submit();
            }).set('labels', {ok:'{{ trans('common.accept') }}', cancel:'{{ trans('common.decline') }}'})
            .show();
    });
});
    </script>
@stop

@section('content')
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        <h1 class="comfortaa orange text-center">@lang('as.embed.booking_form')</h1>

        {{ Form::open(['route' => 'cart.consumer', 'class' => 'form-horizontal well', 'id' => 'confirm-consumer']) }}
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
                    <button id="btn-confirm" class="btn btn-lg btn-success text-uppercase comfortaa">{{ trans('as.bookings.confirm_booking') }}</button>
                </div>
            </div>
        {{ Form::close() }}
        <div id="js-terms" class="soft-hidden">{{ Settings::get('booking_terms')}}</div>
    </div>
</div>
@stop
