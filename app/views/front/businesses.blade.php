@extends ('layouts.default')

@section ('title')
    {{ $title }}
@stop

@if (!empty($meta))
@section ('meta')
    @foreach ($meta as $name => $content)
    <meta name="{{ $name }}" content="{{{ $content }}}">
    @endforeach
@stop
@endif

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/css/swiper.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
    @if(Settings::get('default_layout') === 'layout-3')
    {{ HTML::style(asset_path('as/styles/layout-3.css')) }}
    {{ HTML::style(asset('packages/jquery.raty/jquery.raty.css')) }}
    @endif
@stop

@section('scripts')
    <script>
    VARAA.Search = VARAA.Search || {};
    VARAA.Search.businesses = {{ json_encode($businesses) }};
    VARAA.Search.lat = {{ $lat or 0 }};
    VARAA.Search.lng = {{ $lng or 0 }};
    VARAA.Search.assetPath  = '{{ asset('packages/jquery.raty/images') }}';
@if(!empty($categoryId) && !empty($serviceId))
    VARAA.Search.categoryId = {{ $categoryId }};
    VARAA.Search.serviceId = {{ $serviceId }};
    VARAA.Search.employeeId = {{ $employeeId }};
    VARAA.Search.time = {{ $time }};
@endif

var app = app || {}
app.default_layout = '{{ Settings::get('default_layout') }}'
app.i18n = {
    'select': '@lang('as.embed.cp.select')',
    'pl_service': '@lang('as.embed.cp.pl_service')',
    'sg_service': '@lang('as.embed.cp.sg_service')',
    'first_name': '@lang('as.bookings.first_name')',
    'last_name': '@lang('as.bookings.last_name')',
    'email': '@lang('as.bookings.email')',
    'phone': '@lang('as.bookings.phone')',
    'almost_done': '@lang('as.embed.cp.almost_done')',
    'time': '@lang('as.embed.cp.time')',
    'employee': '@lang('as.embed.cp.employee')',
    'salon': '@lang('as.embed.cp.salon')',
    'price': '@lang('as.embed.cp.price')',
    'service': '@lang('as.embed.cp.service')',
    'details': '@lang('as.embed.cp.details')',
    'how_to_pay': '@lang('as.embed.cp.how_to_pay')',
    'go_back': '@lang('as.embed.cp.go_back')',
    'close': '@lang('common.close')',
    'book': '@lang('as.embed.book')',
    'first_employee': '@lang('as.embed.cp.first_employee')'
}
app.assets = {
    'employee_avatar': '{{ asset_path('core/img/avatar-round.png') }}'
}
app.routes = {
    'business.booking.book': '{{ route('as.bookings.frontend.add') }}',
    'business.booking.book_service': '{{ route('as.bookings.service.front.add') }}',
    'business.booking.services': '{{ route('business.booking.services') }}',
    'business.booking.timetable': '{{ route('business.booking.timetable') }}',
    'business.booking.payments': '{{ route('business.booking.payments') }}',
    'business.booking.employees': '{{ route('business.booking.employees') }}'
}
    </script>

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script(asset('packages/jqueryui/jquery-ui.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.14/jquery.scrollTo.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/js/swiper.jquery.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/history.js/1.8/native.history.min.js') }}
    {{ HTML::script(asset('packages/sticky/jquery.sticky.min.js')) }}
    {{ HTML::script(asset_path(sprintf('as/scripts/%s.js', Settings::get('default_layout')))) }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
    {{ HTML::script(asset('packages/jquery.raty/jquery.raty.js')) }}
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results" id="js-search-results">
    <a href="#" id="js-business-heading">
        <h4 class="heading">
            <i class="fa fa-chevron-left" style="display: none;"></i>
            {{ $heading }}
        </h4>
    </a>

@if (empty($businesses))
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="alert alert-danger">
                <p>{{ trans('common.no_records') }}</p>
            </div>
        </div>
    </div>
@else
    <div id="js-loading" class="loading">
        <p><i class="fa fa-2x fa-spinner fa-spin"></i></p>
    </div>

    <div class="row" id="js-business-list">
        {{-- left sidebar --}}
        <div class="col-sm-3 col-md-3 panel" data-direction="left" id="js-left-sidebar">
            @include ('front.el.sidebar', ['businesses' => $businesses, 'nextPageUrl' => $nextPageUrl])
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-9 col-md-9 panel" data-direction="right" id="js-right-sidebar">
            <div class="hot-offers" id="js-hot-offers" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab-map" aria-controls="tab-map" role="tab" data-toggle="tab">{{ trans('home.map') }}</a></li>
                    {{-- <li role="presentation"><a href="#tab-best-offers" aria-controls="tab-best-offers" role="tab" data-toggle="tab">{{ trans('home.best_offers') }}</a></li> --}}
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tab-map">
                        <div id="js-map-canvas" class="map hidden-xs"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="js-business-single" style="display: none;"></div>
@endif
</div>

@if ($iframeUrl !== null)
<div class="modal homepage-modal fade" id="js-business-modal">
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
