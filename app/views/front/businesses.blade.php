@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css') }}
    {{ HTML::style(asset_path('as/styles/layout-3.css')) }}
@stop

@section('scripts')
    <script>
    VARAA.Search = VARAA.Search || {};
    VARAA.Search.businesses = {{ json_encode($businesses) }};
    VARAA.Search.lat = {{ $lat or 0 }};
    VARAA.Search.lng = {{ $lng or 0 }};
@if(!empty($categoryId) && !empty($serviceId))
    VARAA.Search.categoryId = {{ $categoryId }};
    VARAA.Search.serviceId = {{ $serviceId }};
    VARAA.Search.employeeId = {{ $employeeId }};
    VARAA.Search.time = {{ $time }};
@endif
    </script>

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script(asset('packages/jqueryui/jquery-ui.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script(asset('packages/sticky/jquery.sticky.min.js')) }}
    {{ HTML::script(asset_path('as/scripts/layout-3.js')) }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results" id="js-search-results">
    <a href="#" id="js-business-heading">
        <h4 class="heading">
            <i class="fa fa-chevron-left" style="display: none;"></i>
            {{ $heading }}
        </h4>
    </a>

@if (empty($businesses))
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="alert alert-danger">
                <p>{{ trans('common.no_records') }}</p>
            </div>
        </div>
    </div>
@else
    <div id="js-loading" class="loading">
        <p><i class="fa fa-2x fa-spinner fa-spin"></i></p>
    </div>

    <div class="row">
        {{-- left sidebar --}}
        <div class="col-sm-3 col-md-3 panel" data-direction="left" id="js-business-list">
            <div class="businesses">
            @foreach ($businesses as $business)
                <div class="business js-business" data-id="{{ $business->user_id }}" data-url="{{ $business->business_url }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
                    <p><img src="{{ $business->image }}" alt="" class="img-responsive"></p>
                    <h4><a href="{{ $business->business_url }}" title="">{{{ $business->name }}}</a>
                @if ($business->isUsingAS && (bool) $business->is_booking_disabled === false)
                    <small><span class="label label-success"><i class="fa fa-ticket"></i> {{ trans('home.business.online_booking') }}</span></small>
                @endif
                    {{-- <small>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </small> --}}
                    </h4>
                    <address>{{{ $business->full_address }}}</address>
                </div>
            @endforeach
            </div>

            @if (!empty($nextPageUrl))
            <nav class="text-center show-more">
                <a id="js-show-more" href="{{ $nextPageUrl }}" class="btn btn-default btn-block">Show More</a>
            </nav>
            @endif
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-9 col-md-9 panel" data-direction="right">
            <div class="hot-offers" id="js-hot-offers" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab-map" aria-controls="tab-map" role="tab" data-toggle="tab">{{ trans('home.map') }}</a></li>
                    <li role="presentation"><a href="#tab-best-offers" aria-controls="tab-best-offers" role="tab" data-toggle="tab">{{ trans('home.best_offers') }}</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tab-map">
                        <div id="js-map-canvas" class="map hidden-xs"></div>
                    </div>

                    <div role="tabpanel" class="tab-pane" id="tab-best-offers">
                        <h2 class="heading">{{ trans('home.best_offers') }}</h2>
                        <div class="row">
                        @forelse ($deals as $deal)
                            <div class="col-sm-4 col-md-4">
                                @include ('front.el.deal', ['deal' => $deal])
                            </div>
                        @empty
                            <div class="col-sm-12">
                                <div class="alert alert-info"><p>{{ trans('home.no_offers') }}</p></div>
                            </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-business-single" style="display: none;"></div>
@endif
</div>
@stop
