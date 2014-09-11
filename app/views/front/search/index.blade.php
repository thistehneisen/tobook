@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.search') }}
@stop

@section('styles')
    {{ HTML::style(asset('assets/css/search.css')) }}
@stop

@section('scripts')
    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language=fi') }}
    <script>
    var map;
    function initialize() {
        var mapOptions = {
            zoom: 8,
            center: new google.maps.LatLng({{{ $geocode->getLatitude() }}}, {{{ $geocode->getLongitude() }}})
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@stop

@section('content')
<div class="row">
    <!-- left sidebar -->
    <div class="col-sm-4 col-md-4 col-lg-4 search-left">
        @if ($businesses->count() === 0)
            <p class="alert alert-info">
                {{ trans('search.no_result') }}
            </p>
        @else
            @foreach ($businesses as $biz)
            <div class="result-row row" data-url="{{ route('ajax.show-business', [$biz->id]) }}">
                <img src="{{ asset('assets/img/slides/1.jpg') }}" alt="" class="img-responsive col-md-6" />
                <div class="col-md-6">
                    <h4>{{{ $biz->first_name }}} {{{ $biz->last_name }}}</h4>
                    <p>{{{ $biz->address }}}, {{{ $biz->city }}}</p>
                    <p>60-80€</p>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- right content -->
    <div class="col-sm-8 col-md-8 col-lg-8 search-right">
        {{-- @include('front.search._map')--}}
        <div id="map-canvas" style="
            height: 500px;
            margin: 0px;
            padding: 0px
          "></div>
    </div>
</div>
@stop
