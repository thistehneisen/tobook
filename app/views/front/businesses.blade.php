@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style(asset_path('as/styles/layout-3.css')) }}
@stop

@section('search')
    @include ('front.el.search.default', ['businessCategories' => \App\Core\Models\BusinessCategory::getAll()])
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
                {{ $businesses->links() }}
            </nav>
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-9 col-md-9">

            <div class="hot-offers">
                <div id="map-canvas" class="map hidden-xs"></div>

                <h2 class="heading">Izdevīgi piedāvājumi</h2>
                <div class="row">
                    <div class="col-sm-4 col-md-4">
                        <div class="offer">
                            <p class="image">
                                <img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt="">
                                <span class="badge-wrapper">
                                    <em class="badge">-40%</em>
                                </span>
                            </p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,
                                9 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="offer">
                            <p class="image">
                                <img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt="">
                                <span class="badge-wrapper">
                                    <em class="badge">-40%</em>
                                </span>
                            </p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="offer">
                            <p class="image">
                                <img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt="">
                            </p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business/70" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
