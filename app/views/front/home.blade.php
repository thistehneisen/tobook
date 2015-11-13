@extends ('layouts.default')

@section('title')
    {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.14/jquery.scrollTo.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
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
            @if (App::environment() === 'tobook')
                @include ('front.el.search.form-tobook')
            @else
                @include ('front.el.search.form')
            @endif
        </div>
    </div>
</div>

<div class="container">

<div class="hidden-xs">
     <div class="row">
        <div class="col-sm-12">
            <h2 class="text-center orange comfortaa">{{ trans('Recommend for you') }}</h2>
        </div>
    </div>

    <div class="row">
    @foreach ([1,2,3,4] as $discount)
        <div class="col-sm-3">
            <div class="discount-widget-containter">
                <div class="ribbon-wrapper-green"><div class="ribbon-green">40% OFF</div></div>
                <img data-src="holder.js/100%x180" alt="100%x180" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjwhLS0KU291cmNlIFVSTDogaG9sZGVyLmpzLzEwMCV4MTgwCkNyZWF0ZWQgd2l0aCBIb2xkZXIuanMgMi42LjAuCkxlYXJuIG1vcmUgYXQgaHR0cDovL2hvbGRlcmpzLmNvbQooYykgMjAxMi0yMDE1IEl2YW4gTWFsb3BpbnNreSAtIGh0dHA6Ly9pbXNreS5jbwotLT48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWyNob2xkZXJfMTUxMDExZWVmZTAgdGV4dCB7IGZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0IH0gXV0+PC9zdHlsZT48L2RlZnM+PGcgaWQ9ImhvbGRlcl8xNTEwMTFlZWZlMCI+PHJlY3Qgd2lkdGg9IjE3MSIgaGVpZ2h0PSIxODAiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSI1OS41NTQ2ODc1IiB5PSI5NC41Ij4xNzF4MTgwPC90ZXh0PjwvZz48L2c+PC9zdmc+" data-holder-rendered="true" style="height: 180px; width: 100%; display: block;">
                <div class="discount-service-info">
                     <a href="https://www.wahanda.com/service/1062325-high-quality-strip-waxing-at-house-of-wax-and-massage/" class="offer-title action-uri">High Quality Strip Waxing </a>
                    <span class="business-description">House of Wax &amp; Massage, Bloomsbury</span>
                </div>
                <div class="discount-action">
                    <button class="btn btn-success btn-square">Valitse</button>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>

@if (App::environment() !== 'tobook')
<div class="hidden-xs hidden-sm">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-center orange comfortaa">{{ trans('home.hiw.heading') }}</h2>
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
</div>
@endif

<h2 class="text-center orange comfortaa visible-xs visible-sm">{{ trans('home.hiw.steps.1_text') }}</h2>

@if (App::environment() !== 'tobook')
    <div class="row">
        <ul class="category-imgs">
        @foreach ($masterCategories as $category)
            <li class="col-sm-3"><a href="{{ $category->url }}"><span class="overlay"></span> <img src="{{ $category->image_url }}" alt="" class="img-responsive"><span class="name">{{{ $category->name }}}</span></a></li>
        @endforeach
        </ul>
    </div>
@endif

@if (App::environment() === 'prod')
    <div class="row">
        <h2 class="text-center orange comfortaa">{{ trans('home.on_media') }}</h2>
        <div class="col-sm-3 text-center"><img alt="homeensanomat" src="{{ asset_path('core/img/logos/homeensanomat.png') }}"></div>
        <div class="col-sm-3 text-center"><img alt="ess" src="{{ asset_path('core/img/logos/ess_nimio.png') }}"></div>
        <div class="col-sm-3 text-center"><img alt="keskisuomalainen" src="{{ asset_path('core/img/logos/ksml_logo.png') }}"></div>
        <div class="col-sm-3 text-center"><img alt="kaupalehti" src="{{ asset_path('core/img/logos/kaupalehti.png') }}"></div>
    </div>
    <br>
@endif

@if (App::environment() === 'tobook')
    <div class="row categories" id="js-home-categories">
        <?php $counter = 1; ?>
        @foreach ($masterCategories as $category)
            @if ($category->treatments->isEmpty() === false)
            <div class="col-sm-2 col-md-2">
                <p><img src="{{ $category->icon_url }}" alt=""></p>
                <h4 class="heading"><a href="{{ $category->url }}">{{{ $category->name }}}</a></h4>
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
@endif

</div>

@if ($iframeUrl !== null)
<div class="modal homepage-modal fade" id="js-homepage-modal">
    <div class="modal-dialog homepage-modal-dialog">
        <div class="modal-content homepage-modal-content">
            <div class="modal-body homepage-modal-body">
                <button type="button" class="close homepage-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <iframe src="{{ $iframeUrl }}" frameborder="0" class="homepage-modal-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
@endif
@stop
