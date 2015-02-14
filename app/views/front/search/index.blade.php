@extends('layouts.default')

@section('meta')
    @if (isset($single))
    <meta name="description" content="{{{ $businesses[0]->meta_description }}}">
    <meta name="title" content="{{{ $businesses[0]->meta_title }}}">
    <meta name="keywords" content="{{{ $businesses[0]->meta_keywords }}}">
    @endif
@stop

@section('title')
    @parent ::
    @if (!isset($single)) {{ trans('common.search') }}
    @else {{{ $businesses[0]->name }}}
    @endif
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset_path('as/styles/layout-3.css') }}">
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
    @if(isset($user))
    <script type="text/javascript">
        $(document).ready(function(){
            VARAA.initLayout3({
                isAutoSelectEmployee: <?php echo ($user->asOptions['auto_select_employee']) ? 'true' : 'false';?>
            });
        });
    </script>
    @endif
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section('content')
<div class="row">
    <!-- left sidebar -->
    <div class="col-sm-3 search-left @if (isset($single)) hidden-xs @endif">
        @if ($businesses->count() === 0)
            <p class="alert alert-info">
                {{ trans('search.no_result') }}
            </p>
        @else
            @foreach ($businesses as $item)
            <?php
                $slots = [];
                $count = 0;
            ?>
            <div class="result-row row" data-id="{{ $item->user->id }}" data-url="{{ route('ajax.showBusiness', ['hash' => $item->user->hash, 'id' => $item->user->id, 'l' => 3]) }}">
                <div class="col-sm-12">
                <img src="{{ Util::thumbnail($item->image, 260, 130) }}" alt="" class="img-responsive img-rounded">
                <h5>{{{ $item->name }}} {{ $item->icons }}</h5>
                <p>{{{ $item->full_address }}}</p>
                @foreach ($slots as $slot)
                    <?php if($count === 3) break; ?>
                    <a href="#" data-business-id="{{ $item->user->id }}" data-service-id="{{ $slot['service'] }}" data-employee-id="{{ $slot['employee'] }}" data-hour="{{ $slot['hour'] }}" data-minute="{{ $slot['minute'] }}" class="btn btn-sm btn-default">{{ $slot['time'] }}</a>
                    <?php $count++; ?>
                @endforeach
                </div>
            </div>
            @endforeach
        @endif

    @if (!isset($single)) {{ $businesses->links() }}
    @endif
    </div>

    <!-- right content -->
    <div class="col-sm-9 search-right">
        <div id="js-loading" class="js-loading"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
        <div id="js-business-content">
            {{ isset($single) ? $single : '' }}
        </div>
        <div id="map-canvas" class="hidden-xs"></div>
    </div>
</div>
@stop
