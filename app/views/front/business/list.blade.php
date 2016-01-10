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

@section('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/css/swiper.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
    {{ HTML::style(asset('packages/jquery.raty/jquery.raty.css')) }}
    {{ HTML::style(asset('packages/switchery/switchery.min.css')) }}
@stop

@section('scripts')
    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/js/swiper.jquery.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    {{ HTML::script(asset('packages/jquery.raty/jquery.raty.js')) }}
    {{ HTML::script(asset('packages/switchery/switchery.min.js')) }}
    {{ HTML::script(asset_path('as/scripts/business.js')) }}
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
      $(function() {
        $("#slider-range").slider({
          range: true,
          min: 0,
          max: 500,
          values: [ 0, 500 ],
          slide: function(event, ui) {
            $( "#amount" ).val(ui.values[0] + "€ - " + ui.values[1] + "€");
          },
        });
        $("#amount" ).val($("#slider-range" ).slider( "values", 0 ) + "€ - " + $( "#slider-range" ).slider( "values", 1 ) + "€");
        });

        var app = app || {};

        app.initData = {
            environment : '{{ App::environment() }}',
            cities: {{ json_encode($cities) }},
            districts: {{ json_encode($districts) }},
            assetPath : '{{ asset('packages/jquery.raty/images') }}',
            categories : {{ $mctcs }},
            mcId : {{ $mcId }},
            type : '{{ $type }}',
            discountBusiness : {{ $discountBusiness }},
        }

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
            'first_employee': '@lang('as.embed.cp.first_employee')',
            'coupon_code' : '@lang('as.embed.cp.coupon_code')',
            'save': '@lang('common.save')',
            'validate': '@lang('common.validate')',
            'location_placeholder': '@lang('home.search.location')',
            'query_placeholder': '@lang('home.search.query')',
            'price_range': '@lang('as.bookings.price_range')',
            'show_more': '@lang('home.show_more')',
            'show_map': '@lang('home.show_map')',
            'view_on_map': '@lang('home.view_on_map')',
            'no_record_found': '@lang('common.err.no_record_found')',
            'payment_methods': '@lang('home.business.payment_methods')',
            'payment.venue' : '@lang('user.payment_options.venue')',
            'payment.full' : '@lang('user.payment_options.full')',
            'payment.deposit' : '@lang('user.payment_options.deposit')',
            'online_booking' : '@lang('intro.online_booking')',
            'reviews'    : '@lang('common.reviews')',
            'review'     : '@lang('common.review')',
            'no_review'  : '@lang('common.no_review')',
            'filters'  : '@lang('home.search.filters')',
            'filter_search_results'  : '@lang('home.search.filter_search_results')',
            'only_offpeak_discounts'  : '@lang('home.search.only_offpeak_discounts')',
            'learn_more'  : '@lang('home.search.learn_more')',
            'home.search.current_location' : '@lang('home.search.current_location')',
        };

        app.routes = {
            'business.search': '{{ route('business.search') }}',
            'business.services': '{{ route('ajax.services') }}',
            'baseUrl' : '{{ url() }}'
        };
        app.VaraaBusiness(document.getElementById('business-container'), app.initData.mcId, app.initData.type );
    </script>
@stop

@section('main-classes') front @stop

@section('search')
    @include ('front.el.search.front', ['categories' => $mcs])
@stop

@section('content')
<div class="container business-container" id="business-container">
   <div class="row">
        <div class="col-sm-6">
            &nbsp;
        </div>
        <div class="col-sm-6">
            <i class="fa fa-spin fa-2x fa-spinner"></i>
        </div>
   </div>
</div>
@stop
