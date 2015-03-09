@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style(asset_path('as/styles/layout-3.css')) }}
@stop

@section('scripts')

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('as/scripts/layout-3.js')) }}

    <script>
$(function() {
    new GMaps({
      div: '#map-canvas',
      lat: -12.043333,
      lng: -77.028333
    });
});
    </script>
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results">
    <h4 class="heading">{{ $heading }}</h4>

    <div class="row">
        {{-- left sidebar --}}
        <div class="col-sm-3 col-md-3">
            <div class="businesses">
            @foreach ($businesses as $business)
                <div class="business">
                    <p><img src="{{ $business->image }}" alt="" class="img-responsive"></p>
                    <h4><a href="{{ $business->business_url }}" title="">{{{ $business->name }}}</a>
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

            <nav class="text-center">
                {{ $pagination }}
            </nav>
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-9 col-md-9">

            <div class="hot-offers">
                <div id="map-canvas" class="map hidden-xs"></div>

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
@stop
