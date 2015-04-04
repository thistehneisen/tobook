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
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.14/jquery.scrollTo.min.js') }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
@stop

@section('main-classes') front @stop

@section('search')
    @include ('front.el.search.front', ['categories' => $masterCategories])
@stop

@section('content')
<div class="hero-form">
    <div class="img-bg">
        <div class="container">
            @include ('front.el.search.form')
        </div>
    </div>
</div>

<div class="container">
    <div class="row categories" id="js-home-categories">
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
                            <li><a class="js-filter-link" data-id="{{ $category->id }}" href="#">{{{ $category->name }}} ({{ $category->totalDeals }})</a></li>
                        @endforeach
                            <li class="toggle more"><a href="#">{{ trans('home.more') }} <i class="fa fa-angle-double-right"></i></a></li>
                            <li class="toggle less"><a href="#">{{ trans('home.less') }} <i class="fa fa-angle-double-up"></i></a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            @foreach ($head as $deal)
                <div class="col-sm-4 col-md-4 js-deal js-deal-category-{{ $deal->service->businessCategory->id }}">
                @include ('front.el.deal', ['deal' => $deal])
                </div>
            @endforeach

        @if ($tail->isEmpty() === false)
            @foreach ($tail as $deal)
                <div class="col-sm-4 col-md-4 js-deal js-deal-category-{{ $deal->service->businessCategory->id }}">
                @include ('front.el.deal', ['deal' => $deal])
                </div>
            @endforeach
        @endif
        </div>
    </div>
@endif
</div>
@stop
