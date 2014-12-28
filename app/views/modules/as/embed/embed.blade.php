<!doctype html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    @yield('extra_css')
    @include('modules.as.embed._style')

    <link rel="stylesheet" href="{{ asset_path('as/styles/'.$layout.'.css') }}">

    @section('analytics-tracking')
        @if (App::environment('prod'))
    <script>
        (function (i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function () {
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-53959606-1', 'auto');
        ga('require', 'displayfeatures');
        ga('send', 'pageview');

    </script>
        @endif
    @show
</head>
<body data-hash="{{ $hash }}" data-locale="{{ App::getLocale() }}" class="style-{{ $user->id }}">
    @yield('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif

    {{-- Global --}}
    {{ HTML::script(asset_path('core/scripts/global.js')) }}
    <script>
    VARAA.currentLocale = '{{ App::getLocale() }}';
    $.getJSON("{{ route('ajax.jslocale') }}", function (data) {
        VARAA._i18n = data;
    });
    </script>

    @yield('extra_js')

    <script src="{{ asset_path('as/scripts/'.$layout.'.js') }}"></script>
</body>
</html>
