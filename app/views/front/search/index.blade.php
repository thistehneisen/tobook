@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.search') }}
@stop

@section('styles')
    {{ HTML::style(asset('assets/css/search.css')) }}
@stop

@section('scripts')
    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    <script>
$(function() {
    var gmap = new GMaps({
        div: '#map-canvas',
        lat: {{ $geocode->getLatitude() }},
        lng: {{ $geocode->getLongitude() }},
        zoom: 8
    });

    @foreach ($businesses as $item)
    gmap.addMarker({
        lat: {{ $item->lat }},
        lng: {{ $item->lng }},
        title: '{{ $item->full_name }}'
    });
    @endforeach

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
        });
    });
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
            <div class="result-row row" data-id="{{ $item->id }}" data-url="{{ route('ajax.showBusiness', [$item->id]) }}">
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

        {{ $businesses->links() }}
    </div>

    <!-- right content -->
    <div class="col-sm-8 col-md-8 col-lg-8 search-right">
        <div id="js-loading" class="js-loading"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
        <div id="js-business-content"></div>
        <div id="map-canvas"></div>
    </div>
</div>
@stop
