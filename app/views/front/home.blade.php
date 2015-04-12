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

    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-center orange comfortaa">{{ trans('home.hiw.headings') }}</h2>
        </div>
    </div>

    <div class="row steps">
        <div class="col-sm-offset-3 col-sm-2">
            <h3>{{ trans('home.hiw.steps.1') }}</h3>
            <p>{{ trans('home.hiw.steps.1_text') }}</p>
        </div>
        <div class="col-sm-2">
            <h3>{{ trans('home.hiw.steps.2') }}</h3>
            <p>{{ trans('home.hiw.steps.2_text') }}</p>
        </div>
        <div class="col-sm-2">
            <h3>{{ trans('home.hiw.steps.3') }}</h3>
            <p>{{ trans('home.hiw.steps.3_text') }}</p>
        </div>
    </div>

    <div class="row">
        <ul class="icon-categories">
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/eyelash.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/massage.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/hair.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/body.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/feet.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/nails.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/face.png') }}" alt="" class="img-responsive"></a></li>
            <li class="col-sm-3"><a href="#"><span class="overlay"></span> <img src="{{ asset_path('core/img/front/hairremoval.png') }}" alt="" class="img-responsive"></a></li>
        </ul>
    </div>

    <div class="row categories" id="js-home-categories">
        <?php $counter = 1; ?>
        @foreach ($masterCategories as $category)
            @if ($category->treatments->isEmpty() === false)
            <div class="col-sm-2 col-md-2">
            @if (App::environment() === 'tobook')
                <p><img src="{{ $category->icon_url }}" alt=""></p>
            @endif
                <h4 class="heading">{{{ $category->name }}}</h4>
                <ul class="list-categories">
                @foreach ($category->treatments as $treatment)
                    <li><a href="{{ $treatment->url }}">{{{ $treatment->name }}}</a></li>
                @endforeach
                    <li class="toggle more"><a href="#">{{ trans('home.more') }} <i class="fa fa-angle-double-right"></i></a></li>
                    <li class="toggle less"><a href="#">{{ trans('home.less') }} <i class="fa fa-angle-double-up"></i></a></li>
                </ul>
            </div>
            @endif
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
