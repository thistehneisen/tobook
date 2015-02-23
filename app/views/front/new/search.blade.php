@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('scripts')

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
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

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset_path('as/styles/layout-3.css') }}">
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results">
    <h4 class="heading">
        <span class="keyword">Frizētava</span>,
        <span class="location">Centrs</span>,
        jebkurā dienā,
        jebkurā laikā:
        <span class="results">38 rezultāti</span>
    </h4>

    <div class="row">
        {{-- left sidebar --}}
        <div class="col-sm-3 col-md-3">
            <div class="businesses">
                <div class="business">
                    <p><img src="{{ asset_path('core/img/categories/beauty/beauty1.jpg') }}" alt="" class="img-responsive"></p>
                    <h4><a href="/new/business" title="">Saules salons</a> <small>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </small></h4>
                    <address>Brīvības iela 18, Rīga</address>
                </div>

                <div class="business">
                    <p><img src="{{ asset_path('core/img/categories/beauty/beauty1.jpg') }}" alt="" class="img-responsive"></p>
                    <h4><a href="/new/business" title="">Saules salons</a> <small>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </small></h4>
                    <address>Brīvības iela 18, Rīga</address>
                </div>

                <div class="business">
                    <p><img src="{{ asset_path('core/img/categories/beauty/beauty1.jpg') }}" alt="" class="img-responsive"></p>
                    <h4><a href="/new/business" title="">Saules salons</a> <small>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </small></h4>
                    <address>Brīvības iela 18, Rīga</address>
                </div>

                <div class="business">
                    <p><img src="{{ asset_path('core/img/categories/beauty/beauty1.jpg') }}" alt="" class="img-responsive"></p>
                    <h4><a href="/new/business" title="">Saules salons</a> <small>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </small></h4>
                    <address>Brīvības iela 18, Rīga</address>
                </div>
            </div>

            <nav class="text-center">
                <ul class="pagination">
                    <li>
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
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
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
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
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
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
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                        </div>

                        <div class="offer">
                            <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                            <p>
                                <span class="normal-price">100 EUR</span>
                                <span class="offered-price"><em>50</em>,99 EUR</span>
                            </p>
                            <h4 class="title"><a href="/new/business" title="">Viesnīca «Baltvilla»</a></h4>
                            <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
