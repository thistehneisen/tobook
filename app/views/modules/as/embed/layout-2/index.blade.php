@extends ('modules.as.embed.embed')


@section ('extra_css')
{{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
{{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
@include('modules.as.embed.layout-2._style')
@stop

@section ('extra_js')
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
@stop

@section ('content')
@if(!empty($user->asOptions['style_logo']) || (!empty($user->asOptions['style_banner'])))
<header class="container-fluid as-header">
    <div class="row">
        @if(!empty($user->asOptions['style_logo']))
            <div class="logo"><img class="img-responsive" src="{{ $user->asOptions['style_logo'] }}" alt=""></div>
        @endif
        @if(!empty($user->asOptions['style_banner']))
            <div class="banner"><img class="img-responsive" src="{{ $user->asOptions['style_banner'] }}" alt=""></div>
        @endif
    </div>
</header>
@endif

<div class="container">
    <div class="row">
        <div id="as-select">
            <div class="panel panel-default">
                <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.select_service') }}</strong></div>
                <div class="panel-body">
                    <div class="row" id="as-main-panel">
                        @include ('modules.as.embed.layout-2.services')
                    </div>

                    <div class="as-timetable-wrapper" id="as-timetable"></div>
                </div>
            </div>
        </div>

        <div class="as-checkout" id="as-checkout"></div>
        <div class="as-success" id="as-success">
            <div class="panel panel-default">
                <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.thanks') }}</strong></div>
                <div class="panel-body">
                    <div class="alert alert-success">
                        <p>{{ trans('as.embed.success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="employee-url" value="{{ route('as.embed.employees', Input::all()) }}">
<input type="hidden" name="timetable-url" value="{{ route('as.embed.l2.timetable', Input::all()) }}">
<input type="hidden" name="checkout-url" value="{{ route('as.embed.l2.checkout', Input::all()) }}">
<input type="hidden" name="booking-url" value="{{ route('as.bookings.service.front.add') }}">
@stop

