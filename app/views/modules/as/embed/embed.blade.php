<!doctype html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css') }}
    @yield('extra_css')
    @include('modules.as.embed._style')

    <link rel="stylesheet" href="{{ asset_path('as/styles/'.$layout.'.css') }}">

@if (App::environment() !== 'local')
    {{ Settings::get('head_script') }}
@endif
</head>
<body data-hash="{{ $hash }}" data-locale="{{ App::getLocale() }}" class="style-{{ $user->id }}">
    @yield('content')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/js/bootstrapValidator.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif

    <script>
    window.VARAA = window.VARAA || {};
    </script>

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
    @if($layout !== 'layout-1')
    <script type="text/javascript">
        $(document).ready(function () {
            @if($layout === 'layout-2')
            VARAA.initLayout2({
                isAutoSelectEmployee: <?php echo ($user->asOptions['auto_select_employee']) ? 'true' : 'false';?>
            });
            @elseif($layout === 'layout-3')
            VARAA.initLayout3({
                isAutoSelectEmployee: <?php echo ($user->asOptions['auto_select_employee']) ? 'true' : 'false';?>
            });
            @endif
        });
    </script>
    @endif
</body>
</html>
