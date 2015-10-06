@extends ('layouts.default')

@section('title')
    {{{ !empty($business->meta_title) ? $business->meta_title : $business->name }}}
@stop

@section('meta')
<meta name="description" content="{{{ $business->meta_description }}}">
<meta name="keywords" content="{{{ $business->meta_keywords }}}">
@stop

@section('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/css/swiper.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css') }}
    {{ HTML::style(asset('packages/alertify/css/themes/bootstrap.min.css')) }}
    @if(Settings::get('default_layout') === 'layout-3')
    {{ HTML::style(asset_path('as/styles/layout-3.css')) }}
    @endif
@stop

@section('scripts')
<script>
    // Dump inline data
    VARAA.Search = VARAA.Search || {};
    VARAA.Search.businesses = {{ $businessesJson }};
    VARAA.Search.lat = {{ $lat }};
    VARAA.Search.lng = {{ $lng }};
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
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/Swiper/3.0.6/js/swiper.jquery.min.js') }}

    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    {{ HTML::script(asset('packages/jquery.dotdotdot/jquery.dotdotdot.min.js')) }}
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path(sprintf('as/scripts/%s.js', Settings::get('default_layout')))) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}

    <script>
$(function () {
    var map = new GMaps({
      div: '#js-map-{{ $business->user_id }}',
      lat: {{ $lat }},
      lng: {{ $lng }}
    });
    map.addMarker({
      lat: {{ $lat }},
      lng: {{ $lng }}
    });

    @if(Settings::get('default_layout') === 'layout-cp')
    app.VaraaCPLayout(document.getElementById('js-cp-booking-form'), '{{ $business->user->hash }}')
    @endif

    @if(Settings::get('default_layout') === 'layout-3')
    VARAA.initLayout3({
        isAutoSelectEmployee: false
    });
    @endif
    
    var countPs = function (element) {
      var cnt = 0;
      $(element).children().each(function(){
        cnt += this.tagName === 'P' ? 1 : countPs(this);
      })
      return cnt;
    }

    var countRows = function () {
        var lineHeight = $('div.description').css('line-height')
        lineHeight = parseFloat(lineHeight)
        var height = $('div.description').height()
        var rows = height / lineHeight;
        return rows;
    }

    var ps = countPs($('div.description'));
    var originalContent = $('div.description').html();
    var truncateDescription = function() {
        var originalContent = $('div.description').html();
        if (ps > 1) {
            var firstP = $("p", $('div.description')).first().text();
            $('div.description').find("p").remove();
            $('div.description').prepend(firstP);
            $('a.readmore').show();
        }
       
        var rows = countRows();
        if (rows > 3) {
            $('a.readmore').show();
            $("#business-description").dotdotdot({
                ellipsis : '',
                height   : 60,
                after    : "a.readmore",
            });
        }
       
    }

    $('a.readmore').on('click', function(e){
        e.preventDefault();
        var rows = countRows();
        if (ps == 1) {
            var content = $("#business-description").triggerHandler("originalContent");
            $("#business-description").trigger("destroy");
            $("#business-description").html(content);
            $('a.readmore').hide();
        } else {
            $('div.description').html(originalContent);
        }
    });

    $(document).ready(function(){
        truncateDescription();
    });
});

    </script>
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results" id="js-search-results">
    @include (sprintf('front.el.%s.business', Settings::get('default_layout')))
</div>
@stop
