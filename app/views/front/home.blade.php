@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
@stop

@section('main-classes') front @stop

@section('search')
    @include ('front.el.search.front')
@stop

@section('content')
<div class="hero-form">
    <div class="img-bg">
        <div class="container">
            {{ Form::open(['url' => '/new/search', 'class' => 'form-search', 'method' => 'GET']) }}
                <div class="form-group row">
                    <div class="col-sm-4 col-md-4">
                        <h2 class="heading">{{ trans('home.search.tagline') }}</h2>
                        <div class="input-group margin-bottom-lg">
                            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                            <input class="form-control input-lg" type="text" placeholder="{{ trans('home.search.query') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3 col-md-3">
                        <div class="input-group margin-bottom-lg">
                            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                            <input class="form-control input-lg" type="text" placeholder="{{ trans('home.search.location') }}">
                        </div>
                    </div>

                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-lg btn-success pull-right">{{ trans('home.search.button') }}</button>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4 col-md-4">
                        <div class="datetime-wrapper">
                            <a href="#" class="datetime-link">
                                <i class="fa fa-calendar fa-big"></i> {{ trans('home.search.date') }}
                                <i class="fa fa-chevron-down fa-small"></i>
                            </a>
                            <div class="datetime-control" data-format="YYYY-MM-DD" id="search-select-date">
                                <input type="hidden" name="date">
                            </div>
                        </div>

                        <div class="datetime-wrapper">
                            <a href="#" class="datetime-link">
                                <i class="fa fa-clock-o fa-big"></i> {{ trans('home.search.time') }}
                                <i class="fa fa-chevron-down fa-small"></i>
                            </a>
                            <div class="datetime-control" data-format="HH:mm" id="search-select-time">
                                <input type="hidden" name="time">
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="container">
    <div class="row categories">
        @foreach ($categories as $category)
            <div class="col-sm-2 col-md-2">
                <p><img src="{{ asset_path('core/img/new/icons/'.$category->new_icon.'.png') }}" alt=""></p>
                <h4 class="heading">{{{ $category->name }}}</h4>
                <ul class="list-categories">
                @foreach ($category->children as $child)
                    <li><a href="{{ route('business.category', ['id' => $child->id, 'slug' => $child->slug]) }}">{{{ $child->name }}}</a></li>
                @endforeach
                    <li class="arrow more"><a href="#">{{ trans('home.more') }} <i class="fa fa-chevron-right"></i></a></li>
                    <li class="arrow less"><a href="#">{{ trans('home.less') }} <i class="fa fa-chevron-up"></i></a></li>
                </ul>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="hot-offers">
            <h1 class="heading">{{ trans('home.best_offers') }} (96)</h1>
            <div class="col-sm-4 col-md-4">
                <ul class="list-unstyled filters">
                    <li>{{ trans('home.categories') }}
                        <ul>
                            <li><a href="#">Skaistumkopšana, frizētavas (61)</a></li>
                            <li><a href="#">Auto aprūpe (44)</a></li>
                            <li><a href="#">Veselības aprūpe, zobārstniecība (31)</a></li>
                            <li><a href="#">Atpūta un brūivais laiks (8)</a></li>
                            <li><a href="#">Dzīvnieki (5)</a></li>
                            <li><a href="#">Sports un fitness (3)</a></li>
                        </ul>
                    </li>
                    <li>{{ trans('home.companies_offers') }}
                        <ul>
                            <li><a href="#">Live Active (87)</a></li>
                            <li><a href="#">TOP SHOP (52)</a></li>
                            <li><a href="#">Daces Masules zobārstniecība (22)</a></li>
                            <li><a href="#">Gandrs (9)</a></li>
                            <li><a href="#">Veselības pasaule (5)</a></li>
                            <li><a href="#">ARKOLAT (3)</a></li>
                            <li><a href="#">Optisports (3)</a></li>
                        </ul>
                    </li>
                </ul>
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
                    <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                    <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
                </div>

                <div class="offer">
                    <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                    <p>
                        <span class="normal-price">100 EUR</span>
                        <span class="offered-price"><em>50</em>,99 EUR</span>
                    </p>
                    <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                    <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
                </div>
            </div>

            <div class="col-sm-4 col-md-4">
                <div class="offer">
                    <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                    <p>
                        <span class="normal-price">100 EUR</span>
                        <span class="offered-price"><em>50</em>,99 EUR</span>
                    </p>
                    <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                    <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium illum veniam nostrum deserunt id, est quos, rerum at minima molestiae laboriosam autem quod temporibus corporis, aliquid eos commodi voluptatem nobis.</p>
                </div>

                <div class="offer">
                    <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                    <p>
                        <span class="normal-price">100 EUR</span>
                        <span class="offered-price"><em>50</em>,99 EUR</span>
                    </p>
                    <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                    <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse saepe vel aliquam iusto error mollitia, voluptas ratione inventore. Vitae, ullam, voluptatem ipsa debitis rerum autem natus aliquid provident optio in!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row hot-offers">
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
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
            </div>

            <div class="offer">
                <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                <p>
                    <span class="normal-price">100 EUR</span>
                    <span class="offered-price"><em>50</em>,99 EUR</span>
                </p>
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
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
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
            </div>

            <div class="offer">
                <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                <p>
                    <span class="normal-price">100 EUR</span>
                    <span class="offered-price"><em>50</em>,99 EUR</span>
                </p>
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
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
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Error pariatur aperiam perspiciatis tenetur cum ratione quo dignissimos, recusandae, animi quod et nulla officia facilis perferendis maxime iusto. Nobis, culpa, ea.</p>
            </div>

            <div class="offer">
                <p><img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt=""></p>
                <p>
                    <span class="normal-price">100 EUR</span>
                    <span class="offered-price"><em>50</em>,99 EUR</span>
                </p>
                <h4 class="title"><a href="/new/search" title="">Viesnīca «Baltvilla»</a></h4>
                <p class="desc">Vienvietīgie numuri par vienu nakti - 50,99 EUR, divvietīgie numuri par vienu nakti Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloremque quod eius! Accusantium, dolor adipisci nulla magnam sed rerum assumenda perspiciatis, doloremque error ab totam nam provident, laboriosam, cum sequi.</p>
            </div>
        </div>

    </div>
</div>
@stop
