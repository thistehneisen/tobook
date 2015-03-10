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
                    <li class="toggle more"><a href="#">{{ trans('home.more') }} <i class="fa fa-angle-double-right"></i></a></li>
                    <li class="toggle less"><a href="#">{{ trans('home.less') }} <i class="fa fa-angle-double-up"></i></a></li>
                </ul>
            </div>
        @endforeach
    </div>

@if ($head->isEmpty() === false)
    <div class="row">
        <div class="hot-offers">
            <h1 class="heading">{{ trans('home.best_offers') }} ({{ $totalDeals }})</h1>
            <div class="col-sm-4 col-md-4">
                <ul class="list-unstyled filters">
                    <li>{{ trans('home.categories') }}
                        <ul id="js-category-filter">
                        @foreach ($dealCategories as $category)
                            <li><a href="#">{{{ $category->name }}} ({{ $category->totalDeals }})</a></li>
                        @endforeach
                            <li class="toggle more"><a href="#">{{ trans('home.more') }} <i class="fa fa-angle-double-right"></i></a></li>
                            <li class="toggle less"><a href="#">{{ trans('home.less') }} <i class="fa fa-angle-double-up"></i></a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-sm-4 col-md-4">
            <?php $counter = 1; ?>
            @foreach ($head as $deal)
                @include ('front.el.deal', ['deal' => $deal])

                @if ($counter++ % 2 === 0)
                </div>
                <div class="col-sm-4 col-md-4">
                @endif
            @endforeach
            </div>
        </div>
    </div>
@endif

    @if ($tail->isEmpty() === false)
    <div class="row">
        <div class="hot-offers">
            @foreach ($tail as $deal)
                <div class="col-sm-4 col-md-4">
                @include ('front.el.deal', ['deal' => $deal])
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@stop
