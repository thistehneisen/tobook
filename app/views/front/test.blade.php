@extends ('layouts.default')

@section('title')
    
@stop

@section('meta')

@stop

@section('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/css/swiper.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
@stop

@section('scripts')
    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/js/swiper.jquery.min.js') }}

    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
      $(function() {
        $( "#slider-range" ).slider({
          range: true,
          min: 0,
          max: 500,
          values: [ 75, 300 ],
          slide: function( event, ui ) {
            $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
          }
        });
        $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
          " - $" + $( "#slider-range" ).slider( "values", 1 ) );
      });
    </script>
@stop

@section('main-classes') front @stop

@section('content')
<div class="container business-container">
    <div class="col-sm-3 search-panel">
        <div class="row">
            <i class="fa fa-map-marker fa-2x"></i> View result on map
        </div>
        <div class="row">
            <hr>
            <label for="show_discount">
                <input type="checkbox" name="show_discount"> Only off-peak discounts
            </label>
        </div>
        <div class="row">
            <hr>
            <h4>Filter search result</h4>
            <form class="form-horizontal">
                 <div class="form-group">
                    <div class="col-sm-12">
                      <input type="query" class="form-control" id="query" placeholder="{{ trans('home.search.query') }}">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <input type="location" class="form-control" id="query" placeholder="{{ trans('home.search.location') }}">
                    </div>
                  </div>
            </form>
        </div>
        <div class="row">
            <hr>
            <h4>Price range</h4>
            <p>
              <label for="amount">Price range:</label>
              <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
            </p>
            <div id="slider-range"></div>
        </div>
    </div>
    <div class="col-sm-9 business-list">
        <div class="business-item">
            <h3 class="venue-title">Venue title</h3>
            <span class="venue-desc">
                Addresss <a href="#">Show map &raquo;</a>
            </span>
            <div class="popular-services">
                <div class="row popular-service">
                    <div class="col-xs-8">
                        <a href="">Haircuts and Hairdressing</a>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-orange btn-square pull-right">Valitse</button>
                    </div>
                </div>
                 <div class="row popular-service">
                    <div class="col-xs-8">
                        <a href="">Haircuts and Hairdressing</a>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-orange btn-square pull-right">Valitse</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
