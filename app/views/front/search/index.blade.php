@extends ('layouts.default')

@section('title')
    @parent ::
    @if (!isset($single))
        {{ trans('common.search') }}
    @else
        {{ $businesses[0]->name }}
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

@if(!empty($categoryId) && !empty($serviceId))
$('input:radio[name=category_id][value={{ $categoryId }}], input:radio[name=service_id][value={{ $serviceId }}]').click();

$('input[name=service_id]').on('afterSelect', function () {
    $('input:radio[name=employee_id][value="{{ $employeeId }}"]').click();
});

$('#as-step-3').on('afterShow', function() {
    $('button[data-time="{{ $time }}"]').click();
});
@endif

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
                title: '{{ $item->name }}'
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

            VARAA.initLayout3();

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
        '#js-map-{{ $businesses[0]->user->id }}',
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
    <div class="col-sm-3 col-md-3 col-lg-3 search-left">
        @if ($businesses->count() === 0)
            <p class="alert alert-info">
                {{ trans('search.no_result') }}
            </p>
        @else
            @foreach ($businesses as $item)
             <?php
                //$slots = $item->user->getASNextTimeSlots($now, $now->hour);
                $slots = [];
                $count = 0;
            ?>
            <div class="result-row row" data-id="{{ $item->user->id }}" data-url="{{ route('ajax.showBusiness', ['hash' => $item->user->hash, 'id' => $item->user->id, 'l' => 3]) }}">
                <div class="col-sm-6">
                    <img src="{{ asset($item->image) }}" alt="" class="img-responsive img-rounded">
                </div>
                <div class="col-sm-6">
                    <h4>{{ $item->name }}</h4>
                    <p>{{ $item->full_address }}</p>
                    @foreach ($slots as $slot)
                        <?php if($count === 3) break;?>
                        <a href="#" data-business-id="{{ $item->user->id }}" data-service-id="{{ $slot['service'] }}" data-employee-id="{{ $slot['employee'] }}" data-hour="{{ $slot['hour'] }}" data-minute="{{ $slot['minute'] }}" class="btn btn-sm btn-default">{{ $slot['time'] }}</a>
                    <?php $count++;?>
                    @endforeach
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
    <div class="col-sm-9 col-md-9 col-lg-9 search-right">
        <div id="js-loading" class="js-loading"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
        <div id="js-business-content">
            {{ isset($single) ? $single : '' }}
        </div>
        <div id="map-canvas"></div>
    </div>
</div>
@stop
