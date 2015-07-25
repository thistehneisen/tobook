<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    @section('meta')
        @foreach (Settings::group('meta') as $tag => $content)
            @if ($tag !== 'title') <meta name="{{{ $tag }}}" content="{{{ $content }}}">
            @endif
        @endforeach
    @show

    @if (Config::has('services.paysera.verification'))
    <meta name="verify-paysera" content="{{ Config::get('services.paysera.verification') }}">
    @endif

    <link rel="shortcut icon" type="image/png" href="{{ asset_path('core/img/favicon.png') }}" />

    <title>@yield('title', trans('common.home')) :: {{{ Settings::get('meta_title') }}}</title>

    {{ HTML::style('//fonts.googleapis.com/css?family=Roboto:400,300,600') }}
    {{ HTML::style('//fonts.googleapis.com/css?family=Comfortaa:400,300,700') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css') }}
    @yield('styles')

    {{ HTML::style(asset_path('core/styles/main.css')) }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @if (Request::is('embed/*') === false && App::environment() === 'prod')
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?1b7hTzZJGpnsLXf2RNKlDaznUTHizLMr";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->
    @endif

    {{-- Disable user custom scripts in local environment --}}
@if (App::environment() !== 'local')
    {{ Settings::get('head_script') }}
@endif

    {{ Lomake::renderHead() }}
</head>
<body @if(!empty($hash)) data-hash="{{ $hash }}" @endif data-locale="{{ App::getLocale() }}" data-js-locale="{{ route('ajax.jslocale') }}" data-geo-url="{{ route('search.location') }}" data-lat="{{ $lat }}" data-lng="{{ $lng }}">
    <div class="container-fluid top-alert warning" id="js-top-alert">
        <p>Please allow us to know your location to serve you better. Click here to try again.</p>
    </div>
    @section('header')
    <header class="header">
        <nav class="main-nav container">
            @section('header-logo')
            <a href="{{ route('home') }}" class="logo pull-left hidden-print">
                <img src="{{ asset_path('core/img/mainlogo.png') }}" alt="">
            </a>
            @show

            @section('main-nav')
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse pull-right" id="main-menu">
                <ul class="nav navbar-nav">
                    {{-- Language switcher --}}
                    <li>
                        <select id="js-language-switcher" class="form-control">
                            @foreach (Config::get('varaa.languages') as $locale)
                            <option value="{{ UrlHelper::localizeCurrentUrl($locale) }}" {{ Config::get('app.locale') === $locale ? 'selected' : '' }}>{{ strtoupper($locale) }}</option>
                            @endforeach
                        </select>
                    </li>

                    {{-- Welcome message --}}
                    @if (Confide::user())
                    <li class="hidden-sm hidden-xs">
                        <p>
                        @if (Session::get('stealthMode') !== null) {{ trans('common.logged_in_as') }}
                            @if (Confide::user()->is_business)
                                <span class="label label-bg label-success">{{{ Confide::user()->business->name }}}</span>
                            @elseif (Confide::user()->is_consumer)
                                <span class="label label-warning">{{{ Confide::user()->email }}}</span>
                            @endif
                        @else {{ trans('common.welcome') }},
                            @if (Confide::user()->is_business)
                                <strong>{{{ Confide::user()->business->name }}}</strong>
                            @elseif (Confide::user()->is_consumer)
                                <strong>{{{ Confide::user()->first_name }}}</strong>
                            @else
                                <strong>{{{ Confide::user()->email }}}</strong>
                            @endif
                            !
                        @endif
                        </p>
                    </li>
                    @endif

                    {{-- Logged in --}}
                    @if (Confide::user()) {{-- Admin --}}
                        @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
                        <li><a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-rocket"></i>
                            {{ trans('common.admin') }}
                        </a></li>
                        @endif

                        {{-- Business user --}}
                        @if (Confide::user()->is_business)
                            <li class="dropdown">
                                <a href="{{ route('dashboard.index') }}">
                                    <i class="fa fa-star"></i>
                                    {{ trans('common.dashboard') }}
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach (Confide::user()->modules as $module => $routeName)
                                        <li><a href="{{ route($routeName) }}">{{ trans('dashboard.'.$module) }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        <li><a href="{{ route('user.profile') }}">
                            <i class="fa fa-user"></i>
                            {{ trans('common.my_account') }}
                        </a></li>
                        <li><a href="{{ route('auth.logout') }}">
                            <i class="fa fa-sign-out"></i>
                            {{ trans('common.sign_out') }}
                        </a></li>

                    @else {{-- Not logged in --}}

                        <li class="dropdown">
                            <a href="{{ route('auth.login') }}">
                                <i class="fa fa-sign-in"></i> {{ trans('common.sign_in_header') }}
                            </a>
                        </li>

                    @endif
                </ul>
            </div>
            @show
        </nav>

        @section('search')
            @include ('front.el.search.default')
        @show
    </header>
    @show

    @yield('nav-admin')

    <main role="main" class="@section('main-classes')container @show main">
        @yield('content')
    </main>

    @yield('extra_modals')

    @yield('iframe')

    @section('footer')
        @include ($footerView)
    @show

    {{-- External libs --}}
    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.4/typeahead.bundle.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/mithril/0.2.0/mithril.min.js') }}
    <script>
var VARAA = VARAA || {}
VARAA.routes = VARAA.routes || {}
VARAA.routes.updateLocation = '{{ route('search.location') }}'

var app =  app || {}
app.prefetch = app.prefetch || {}
app.prefetch.cities = {{ json_encode($cities) }}
app.prefetch.districts = {{ json_encode($districts) }}

app.lang = app.lang || {}
app.lang.home = {
    search: {
        current_location: '@lang('home.search.current_location')'
    }
}
    </script>
    {{ HTML::script(asset_path('core/scripts/global.js')) }}
    {{ HTML::script(asset_path('core/scripts/main.js')) }}

    @yield('scripts')

    {{-- Disable user custom scripts in local environment --}}
@if (App::environment() !== 'local')
    {{ Settings::get('bottom_script') }}
@endif
</body>
</html>
