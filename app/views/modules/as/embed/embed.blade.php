<!doctype html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
    <link rel="stylesheet" href="{{ asset('assets/css/as/layout-1.css') }}">
    <style type="text/css">
        @if(!empty($user->asOptions['style_background']) || !empty($user->asOptions['style_text_color']))
        body {
            @if(!empty($user->asOptions['style_background']))
            background-color: {{ $user->asOptions['style_background'] }} !important;
            @endif
            {{--
            @if(!empty($user->asOptions['style_text_color']))
            color: {{ $user->asOptions['style_text_color'] }};
            @endif
            --}}
        }
        @endif

        @if(!empty($user->asOptions['style_heading_color']))
        .panel-heading {
            color: {{ $user->asOptions['style_heading_color'] }} !important;
        }
        @endif

        @if(!empty($user->asOptions['style_heading_background']))
        .panel-heading {
            background: {{ $user->asOptions['style_heading_background'] }} !important;
        }
        @else
        .panel-heading {
            background: #fff !important;
        }
        @endif

        @if(!empty($user->asOptions['style_main_color']))
        .list-group-item-heading {
            color: {{ $user->asOptions['style_main_color'] }} !important;
        }
        .datepicker-days .today.day,
        .datepicker-days .today.day:hover,
        .datepicker-days .today.day:hover:hover,
        .datepicker-days .today.active.day {
            background-color: {{ $user->asOptions['style_main_color'] }} !important;
            border-color: {{ $user->asOptions['style_main_color'] }} !important;
            color: #fff;
        }
        @endif

        @if(!empty($user->asOptions['style_main_color']))
        .list-group-item-heading {
            color: {{ $user->asOptions['style_main_color'] }} !important;
        }
        .datepicker-days .today.day,
        .datepicker-days .today.day:hover,
        .datepicker-days .today.day:hover:hover,
        .datepicker-days .today.active.day {
            background-color: {{ $user->asOptions['style_main_color'] }} !important;
            border-color: {{ $user->asOptions['style_main_color'] }} !important;
            color: #fff;
        }
        @endif

        @if (!empty($user->asOptions['style_custom_css']))
        {{ $user->asOptions['style_custom_css'] }}
        @endif

        @if (!empty($user->asOptions['hide_prices']) && $user->asOptions['hide_prices'] === '1')
        .price-tag {
            display:none;
        }
        @endif
    </style>
</head>
<body class="style-{{ $user->id }}">
    @yield('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    @if(App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    <script src="{{ asset('assets/js/as/embed.js') }}"></script>
    <script>
    $(document).ready(function () {
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(),
            todayBtn: true,
            todayHighlight: true,
            weekStart: 1,
            language: '{{ App::getLocale() }}'
        }).on('changeDate', function (e) {
            if (window.location.href.indexOf('date') != -1) {
                window.location.href = window.location.href.replace(new RegExp("date=.*?(&|$)", 'g'), "date=" + e.format());
            } else {
                 window.location.href = window.location.href + '?date=' + e.format();
            }
        });
        $("#datepicker").datepicker("update", new Date({{ $date->year }},{{ $date->month - 1}},{{ $date->day }}));
        $('#txt-date').val('{{ $date->toDateString() }}');
        var slots = (parseInt($('#booking_length').val(), 10) / 15);
        var beforeSlots = (parseInt($('#booking_before').val(), 10) / 15);
        var totalSlots = (parseInt($('#booking_length').val(), 10) / 15) - 1;//subtract it self
        $('li.slot').each(function () {
            var len = $(this).nextAll('.active').length;
            var plustime = (parseInt($(this).data('plustime'), 10) / 15);

            if (len < slots) {
                $(this).removeClass('active');
                $(this).addClass('inactive');
            }
            var lenBefore = $(this).prevUntil('li.booked').length;
            if (lenBefore < beforeSlots) {
                $(this).removeClass('active');
                $(this).addClass('inactive');
            }
            var len = $(this).nextUntil('li.booked').length;
            if(len < (totalSlots + plustime)){
                $(this).removeClass('active');
                $(this).addClass('inactive');
            }
        });
    });
    </script>
</body>
</html>
