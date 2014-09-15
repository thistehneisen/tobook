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
    new GMaps({
        div: '#map-canvas',
        lat: {{ $geocode->getLatitude() }},
        lng: {{ $geocode->getLongitude() }},
        zoom: 8
    });

    var loading = $('#js-loading'),
        content = $('#js-business-content'),
        map = $('#map-canvas');

    $('div.result-row').click(function(e) {
        var $this = $(this);

        e.preventDefault();
        loading.show();

        $.ajax({
            url: $this.data('url'),
            type: 'GET'
        }).done(function(html) {
            loading.hide();
            map.hide();

            content.html(html);
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
            <div class="result-row row" data-url="{{ route('ajax.showBusiness', [$item->id]) }}">
                <img src="{{ asset('assets/img/slides/1.jpg') }}" alt="" class="img-responsive col-md-6" />
                <div class="col-md-6">
                    <h4>{{ $item->full_name }}</h4>
                    <p>{{ $item->full_address }}</p>
                    <p>60-80€</p>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- right content -->
    <div class="col-sm-8 col-md-8 col-lg-8 search-right">
        <div id="js-loading" class="js-loading"><i class="fa fa-spinner fa-spin fa-4x"></i></div>
        <div id="js-business-content"></div>
        <div id="map-canvas"></div>
    </div>
</div>
@stop
