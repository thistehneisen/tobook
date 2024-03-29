@extends ('layouts.default')

@section('title')
    {{ trans('common.home') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
    {{ HTML::style(asset('packages/jquery.raty/jquery.raty.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.14/jquery.scrollTo.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset('packages/jquery.raty/jquery.raty.js')) }}
    <script type="text/javascript">
    $(document).ready(function(){
        var assetPath = '{{ asset('packages/jquery.raty/images') }}';
        $('.raty').raty({
            scoreName: function(){
                return $(this).data('name');
            },
            score: function(){
              return $(this).data('score')
            },
            starOff : assetPath + '/star-off.png',
            starOn  : assetPath + '/star-on.png',
            starHalf : assetPath + '/star-half.png',
            readOnly: true  
        });
        $('.raty').raty('reload');
        $('.service-tooltip').tooltip({
          selector: '',
          placement: 'top',
          container: 'body',
          trigger: 'hover',
          html: true
        });
    });
    </script>
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

@if(Settings::get('show_discount_suggestion'))
<div class="hidden-xs">
     <div class="row">
        <div class="col-sm-12">
            <h2 class="text-center orange comfortaa">{{ trans('home.discount.heading') }}</h2>
        </div>
    </div>
    <div class="row">
    @foreach ($randomBusinesses as $business)
        <div class="col-sm-3">
            <div class="discount-widget-containter">
                @if($business->discountPercent> 0)<div class="ribbon-wrapper"><div class="ribbon-red">-{{ $business->discountPercent }}%</div></div>@endif
                <a href="{{ route('business.index', ['id' => $business->user_id, 'slug' => $business->slug]) }}" class="offer-title action-uri"><img style="height: 180px; width: 100%; display: block;" src="{{ $business->image }}" alt="{{ $business->name }}"></a>
                {{-- <div class="discount-service-rating">
                    <span class="raty" data-score="{{ $business->reviewScore }}"></span><span class="pull-right">{{ $business->reviewCount }} {{ trans('common.review') }}</span>
                </div> --}}
                <div class="discount-service-info">
                    <a href="{{ route('business.index', ['id' => $business->user_id, 'slug' => $business->slug, 'serviceId' => $business->randomMostDiscountedService->id ]) }}">{{ $business->name }}</a>
                     <a title="{{{ $business->randomMostDiscountedService->description }}}" href="{{ route('business.index', ['id' => $business->user_id, 'slug' => $business->slug, 'serviceId' => $business->randomMostDiscountedService->id ]) }}" class="offer-title action-uri service-tooltip">{{ $business->randomMostDiscountedService->name }} - 
                     @if ($business->discountPercent > 0) 
                     <i class="fa fa-tag"></i>{{ $business->randomMostDiscountedService->price *  ( 1 - ($business->discountPercent /100)) }}&euro;
                     @else
                     {{ $business->randomMostDiscountedService->price }}&euro;
                     @endif
                     </a>
                    <span class="business-description">{{ $business->address }} {{ $business->city }}</span>
                </div>
                <div class="discount-action">
                    <a href="{{ route('business.index', ['id' => $business->user_id, 'slug' => $business->slug, 'serviceId' => $business->randomMostDiscountedService->id]) }}" class="btn btn-square btn-success">{{ trans('common.select') }}</a>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
@endif

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
    @foreach (array_chunk($masterCategories->all(), 6) as $id => $groupCategories)
        <div class="row categories" id="js-home-categories" @if($id) style="margin-top:-70px" @endif>
        @foreach($groupCategories as $category)
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
    @endforeach
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
