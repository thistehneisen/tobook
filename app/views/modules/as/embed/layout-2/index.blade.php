@extends ('modules.as.embed.embed')


@section ('extra_css')
{{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
{{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
@include('modules.as.embed.layout-2._style')
@stop

@section ('extra_js')
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
{{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/mithril/0.2.0/mithril.min.js') }}
<script type="text/javascript">
    var app = app || {}
app.i18n = {
    'as.embed.layout_2.choose'      : '@lang('as.embed.layout_2.choose')',
    'as.embed.layout_2.unavailable' : '@lang('as.embed.layout_2.unavailable')',
    'common.short.week'             : '@lang('common.short.week')',
    'common.short.Jan'              : '@lang('common.short.jan')',
    'common.short.Feb'              : '@lang('common.short.feb')',
    'common.short.Mar'              : '@lang('common.short.mar')',
    'common.short.Apr'              : '@lang('common.short.apr')',
    'common.short.May'              : '@lang('common.short.may')',
    'common.short.Jun'              : '@lang('common.short.jun')',
    'common.short.July'             : '@lang('common.short.july')',
    'common.short.Aug'              : '@lang('common.short.aug')',
    'common.short.Sep'              : '@lang('common.short.sep')',
    'common.short.Oct'              : '@lang('common.short.oct')',
    'common.short.Nov'              : '@lang('common.short.nov')',
    'common.short.Dec'              : '@lang('common.short.dec')',
    'common.select'                 : '@lang('common.select')',
}
</script>
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

@if((boolean)$user->asOptions['announcement_enable'])
<div class="container alert alert-info announcement hidden-print hidden-xs hidden-sm">
    <p>{{ trans('as.index.heading') }}</p>
    <p>{{ $user->asOptions['announcement_content'] }}</p>
</div>
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

