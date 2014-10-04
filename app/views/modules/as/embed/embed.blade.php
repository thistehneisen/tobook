<!doctype html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    @yield('extra_css')

    <link rel="stylesheet" href="{{ asset('assets/css/as/'.$layout.'.css').(Config::get('app.debug') ? '?v='.time() : '') }}">
    <style type="text/css">
        @if(!empty($user->asOptions['style_background']) || !empty($user->asOptions['style_text_color']))
        body {
            @if(!empty($user->asOptions['style_background']))
            background-color: {{ $user->asOptions['style_background'] }} !important;
            @endif
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
<body data-hash="{{ $hash }}" data-locale="{{ App::getLocale() }}" class="style-{{ $user->id }}">
    @yield('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    @if(App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    @yield('extra_js')

    <script src="{{ asset('assets/js/as/'.$layout.'.js').(Config::get('app.debug') ? '?v='.time() : '') }}"></script>
</body>
</html>
