@extends ('layouts.default')

@section('title')
    @parent ::
    @if (!isset($single))
        {{ trans('common.search') }}
    @else
        {{ $businesses[0]->business_name }}
    @endif
@stop

@section('styles')
    {{ HTML::style(asset('assets/css/search.css')) }}
    <link rel="stylesheet" href="{{ asset('assets/css/as/layout-3.css').(Config::get('app.debug') ? '?v='.time() : '') }}">
@stop

@section('scripts')
    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    {{ HTML::script(asset('assets/js/as/layout-3.js').(Config::get('app.debug') ? '?v='.time() : '')) }}
    <script>
$(function() {

var renderMap = function(mapId, lat, lng, markers) {
    var gmap = new GMaps({
        div: mapId,
        lat: lat,
        lng: lng,
        zoom: 8
    });

    if (typeof markers !== 'undefined') {
        for (var i in markers) {
            gmap.addMarker(markers[i]);
        }
    }

    return gmap;
};

var applyCountdown = function(elems) {
    elems.each(function() {
        var $this = $(this);

        $this.countdown({
            until: new Date($this.data('date')),
            compact: true,
            layout: '{hnn}{sep}{mnn}{sep}{snn}',
        });
    });
};

// Init
applyCountdown($('span.countdown'));

@if (!isset($single))
    renderMap(
        '#map-canvas',
        {{ $lat }},
        {{ $lng }},
        [
    @foreach ($businesses as $item)
            {
                lat: {{ $item->lat }},
                lng: {{ $item->lng }},
                title: '{{ $item->full_name }}'
            },
    @endforeach
        ]
    );

    var loading = $('#js-loading'),
        content = $('#js-business-content'),
        map = $('#map-canvas');

    $('div.result-row').click(function(e) {
        e.preventDefault();
        var $this = $(this);

        // If the current content is of this business, we don't need to fire
        // another AJAX
        if (content.data('current') === $this.data('id')) {
            return;
        }

        // Highlight selected row
        $('div.result-row').removeClass('selected');
        $this.addClass('selected');

        loading.show();

        $.ajax({
            url: $this.data('url'),
            type: 'GET'
        }).done(function(html) {
            loading.hide();
            map.hide();

            content.html(html);

            // Set current business flag
            content.data('current', $this.data('id'));

            // Now render the map
            var mapId = '#js-map-'+$this.data('id'),
                lat = $(mapId).data('lat'),
                lng = $(mapId).data('lng');

            var gmap = new GMaps({
                div: mapId,
                lat: lat,
                lng: lng,
                zoom: 8
            });
            gmap.addMarker({
                lat: lat,
                lng: lng
            });

            // Countdown
            applyCountdown(content.find('span.countdown'));
        });
    });
@else
    renderMap(
        '#js-map-{{ $businesses[0]->id }}',
        {{ $lat }},
        {{ $lng }},
        [
            {
                lat: {{ $lat }},
                lng: {{ $lng }}
            }
        ]
    );
@endif
});
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
            @foreach ($businesses as $item)
            <div class="result-row row" data-id="{{ $item->id }}" data-url="{{ route('ajax.showBusiness', ['hash' => $item->hash, 'id' => $item->id, 'l' => 3]) }}">
                <div class="col-md-6">
                    <img src="{{ asset($item->image) }}" alt="" class="img-responsive img-rounded">
                    {{--
                    <div class="text-center">
                        <ul class="list-inline">
                            <li><i class="text-warning fa fa-star"></i></li>
                            <li><i class="text-warning fa fa-star"></i></li>
                            <li><i class="text-warning fa fa-star"></i></li>
                        </ul>
                    </div>
                    --}}
                </div>
                <div class="col-md-6">
                    <h4>{{ $item->business_name }}</h4>
                    <p>{{ $item->full_address }}</p>
                    {{--
                    <p>60-80€</p>
                    --}}
                </div>
            </div>
            @endforeach
        @endif

    @if (!isset($single))
        {{ $businesses->links() }}
    @endif
    </div>

    <!-- right content -->
    <div class="col-sm-8 col-md-8 col-lg-8 search-right">
        <div id="js-loading" class="js-loading"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
        <div id="js-business-content">
            {{ isset($single) ? $single : '' }}
        </div>
        <div id="map-canvas"></div>
    </div>
</div>
@stop
