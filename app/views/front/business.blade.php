@extends ('layouts.default')

@section('title')
    @parent :: {{{ $business->name }}}
@stop

@section('scripts')
    <script>
    // Dump inline data
    VARAA.Search = VARAA.Search || {};
    VARAA.Search.businesses = {{ $businessesJson }};
    VARAA.Search.lat = {{ $lat }};
    VARAA.Search.lng = {{ $lng }};
@if(!empty($categoryId) && !empty($serviceId))
    VARAA.Search.categoryId = {{ $categoryId }};
    VARAA.Search.serviceId = {{ $serviceId }};
    VARAA.Search.employeeId = {{ $employeeId }};
    VARAA.Search.time = {{ $time }};
@endif
    </script>

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script(asset_path('as/scripts/layout-3.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}

    <script>
$(function () {
    new GMaps({
      div: '#js-map-{{ $business->user_id }}',
      lat: {{ $lat }},
      lng: {{ $lng }}
    });

    VARAA.initLayout3({
        isAutoSelectEmployee: false
    });
});
    </script>
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset_path('as/styles/layout-3.css') }}">
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results" id="js-search-results">
    @include ('front.el.business')
</div>
@stop
