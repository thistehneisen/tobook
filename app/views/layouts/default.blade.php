<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>
        @section('title')
        Varaa
        @show
    </title>

    {{ HTML::style('//fonts.googleapis.com/css?family=Roboto:400,300,600') }}
    {{ HTML::style('//fonts.googleapis.com/css?family=Comfortaa:400,300,700') }}
    {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css') }}
    {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css') }}
    @yield('styles')

    {{-- Increment the version number to force clear cache --}}
    {{ HTML::style(asset('assets/css/main.css?v=00001')) }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @section('analytics-tracking')
        @if (App::environment('prod'))
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
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
<body @if(!empty($hash)) data-hash="{{ $hash }}" @endif data-locale="{{ App::getLocale() }}">
    @section('header')
    <header class="header container-fluid">
        <nav class="main-nav container">
            @section('header-logo')
            <a href="{{ route('home') }}" class="logo pull-left">varaa<span>.com</span></a>
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
                        <select class="form-control">
                            @foreach (Config::get('varaa.languages') as $locale)
                            <option value="{{ UrlHelper::localizeCurrentUrl($locale) }}" {{ Config::get('app.locale') === $locale ? 'selected' : '' }}>{{ strtoupper($locale) }}</option>
                            @endforeach
                        </select>
                    </li>

                    {{-- Welcome message --}}
                    @if (Confide::user())
                    <li class="hidden-sm hidden-xs">
                        <p>
                        @if (Session::get('stealthMode') !== null)
                            You're now login as <strong>{{ Confide::user()->username }}</strong>
                        @else
                            {{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!
                        @endif
                        </p>
                    </li>
                    @endif

                    {{-- Show cart to consumer only --}}
                    @if (!Confide::user() || Confide::user()->is_consumer === true)
                    <li class="cart">
                        <a data-cart-url="{{ route('cart.index') }}" href="#" id="header-cart" data-toggle="popover">
                            <i class="fa fa-shopping-cart"></i> <span class="content"><i class="fa fa-spinner fa-spin"></i></span>
                        </a>
                    </li>
                    @endif

                    {{-- Logged in --}}
                    @if (Confide::user())

                        {{-- Business user --}}
                        @if (Confide::user()->is_consumer === false)
                            {{-- Admin --}}
                            @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
                            <li><a href="{{ route('admin.index') }}">
                                <i class="fa fa-rocket"></i>
                                {{ trans('common.admin') }}
                            </a></li>
                            @endif
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

        <div class="search-wrapper row">
            @section('main-search')
                <div id="category-menu" class="hidden-sm hidden-xs">
                    <ul class="nav navbar-nav">
                        @foreach ($businessCategories as $category)
                        <li class="dropdown">
                            <a href="{{ route('search') }}?q={{ urlencode($category->name) }}">
                                <i class="fa {{ $category->icon() }}"></i>
                                {{ $category->name }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                            @foreach ($category->children as $child)
                                <li><a href="{{ route('search') }}?q={{ urlencode($child->name) }}">{{ $child->name }}</a></li>
                            @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline']) }}
                    <div class="form-group">
                        <div class="input-group input-group">
                            <div class="input-group-addon"><i class="fa fa-search"></i></div>
                            <input type="text" class="form-control typeahead" id="js-queryInput" name="q" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('q') }}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group">
                            <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                            <input type="text" class="form-control" id="js-locationInput" name="location" placeholder="{{ trans('home.search.location') }}" value="{{{ Input::get('location') }}}" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
                {{ Form::close() }}
            @show
        </div>
    </header>
    @show

    @yield('nav-admin')

    <main role="main" class="@section('main-classes')container @show main">
        @yield('content')
    </main>

    @yield('iframe')

    @section('footer')
    <footer class="container-fluid footer">
        <div class="container">
            <div class="col-md-4 col-lg-4">
                <p>&copy; {{ date('Y') }} <a href="http://varaa.com">varaa.com</a></p>
                <ul class="list-unstyled list-inline list-social-networks">
                    <li><a href="{{ Setting::get('facebook-page') }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="{{ Setting::get('google-page') }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="{{ Setting::get('rss-page') }}" target="_blank"><i class="fa fa-rss"></i></a></li>
                    <li><a href="{{ Setting::get('pinterest-page') }}" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                    <li><a href="{{ Setting::get('linkedin-page') }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>

            <div class="col-md-4 col-lg-4">
                <ul class="list-unstyled">
                    <li><a href="{{ route('front.about') }}">{{ trans('About us') }}</a></li>
                    <li><a href="{{ route('front.contact') }}">{{ trans('Contact us') }}</a></li>
                    <li><a href="{{ route('intro-business') }}">{{ trans('common.for_business') }}</a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4">
                <ul class="list-unstyled">
                    <li><a href="{{ route('front.partners') }}">{{ trans('Partners') }}</a></li>
                    <li><a href="{{ route('front.resellers') }}">{{ trans('Resellers') }}</a></li>
                    <li><a href="{{ route('front.media') }}">{{ trans('Media companies') }}</a></li>
                    <li><a href="{{ route('front.directories') }}">{{ trans('Directories') }}</a></li>
                </ul>
            </div>
        </div>
    </footer>
    @show

    {{-- External libs --}}
    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.4/typeahead.bundle.min.js') }}

    {{-- Global --}}
    {{ HTML::script(asset('assets/js/global.js?v=00001')) }}
    <script>
    VARAA.currentLocale = '{{ App::getLocale() }}';
    $.getJSON("{{ route('ajax.jslocale') }}", function (data) {
        VARAA._i18n = data;
    });
    </script>

    {{-- Others --}}
    {{ HTML::script(asset('assets/js/main.js?v=00001')) }}
    @yield('scripts')

    {{-- Freshchat --}}
    @if (App::environment('prod'))
    <script type='text/javascript'>if(new Date().getTime() < 1414239795000){var fc_CSS=document.createElement('link');fc_CSS.setAttribute('rel','stylesheet');var isSecured = (window.location && window.location.protocol == 'https:');fc_CSS.setAttribute('type','text/css');fc_CSS.setAttribute('href',((isSecured)? 'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets1.chat.freshdesk.com')+'/css/visitor.css');document.getElementsByTagName('head')[0].appendChild(fc_CSS);var fc_JS=document.createElement('script'); fc_JS.type='text/javascript';fc_JS.src=((isSecured)?'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets.chat.freshdesk.com')+'/js/visitor.js';(document.body?document.body:document.getElementsByTagName('head')[0]).appendChild(fc_JS);window.freshchat_setting= 'eyJmY19pZCI6ImEyODllNDc4YWI3NDlhM2ZkNjY4YmE2YTljZTQ2MzIwIiwiYWN0aXZlIjp0cnVlLCJzaG93X29uX3BvcnRhbCI6ZmFsc2UsInBvcnRhbF9sb2dpbl9yZXF1aXJlZCI6ZmFsc2UsInNob3ciOjEsInJlcXVpcmVkIjoyLCJoZWxwZGVza25hbWUiOiJ2YXJhYS5jb20iLCJuYW1lX2xhYmVsIjoiTmFtZSIsIm1haWxfbGFiZWwiOiJFbWFpbCIsInBob25lX2xhYmVsIjoiUGhvbmUgTnVtYmVyIiwidGV4dGZpZWxkX2xhYmVsIjoiVGV4dGZpZWxkIiwiZHJvcGRvd25fbGFiZWwiOiJEcm9wZG93biIsIndlYnVybCI6InZhcmFhLmZyZXNoZGVzay5jb20iLCJub2RldXJsIjoiY2hhdC5mcmVzaGRlc2suY29tIiwiZGVidWciOjEsIm1lIjoiTWUiLCJleHBpcnkiOjE0MTQyMzk3OTUwMDAsImVudmlyb25tZW50IjoicHJvZHVjdGlvbiIsImRlZmF1bHRfd2luZG93X29mZnNldCI6MzAsImRlZmF1bHRfbWF4aW1pemVkX3RpdGxlIjoiQ2hhdCBpbiBwcm9ncmVzcyIsImRlZmF1bHRfbWluaW1pemVkX3RpdGxlIjoiTGV0J3MgdGFsayEiLCJkZWZhdWx0X3RleHRfcGxhY2UiOiJZb3VyIE1lc3NhZ2UiLCJkZWZhdWx0X2Nvbm5lY3RpbmdfbXNnIjoiT2RvdGV0YWFuIGVkdXN0YWphYSIsImRlZmF1bHRfd2VsY29tZV9tZXNzYWdlIjoiSGkhIEhvdyBjYW4gd2UgaGVscCB5b3UgdG9kYXk/IiwiZGVmYXVsdF93YWl0X21lc3NhZ2UiOiJPbmUgb2YgdXMgd2lsbCBiZSB3aXRoIHlvdSByaWdodCBhd2F5LCBwbGVhc2Ugd2FpdC4iLCJkZWZhdWx0X2FnZW50X2pvaW5lZF9tc2ciOiJ7e2FnZW50X25hbWV9fSBoYXMgam9pbmVkIHRoZSBjaGF0IiwiZGVmYXVsdF9hZ2VudF9sZWZ0X21zZyI6Int7YWdlbnRfbmFtZX19IGhhcyBsZWZ0IHRoZSBjaGF0IiwiZGVmYXVsdF90aGFua19tZXNzYWdlIjoiVGhhbmsgeW91IGZvciBjaGF0dGluZyB3aXRoIHVzLiBJZiB5b3UgaGF2ZSBhZGRpdGlvbmFsIHF1ZXN0aW9ucywgZmVlbCBmcmVlIHRvIHBpbmcgdXMhIiwiZGVmYXVsdF9ub25fYXZhaWxhYmlsaXR5X21lc3NhZ2UiOiJXZSBhcmUgc29ycnksIGFsbCBvdXIgYWdlbnRzIGFyZSBidXN5LiBQbGVhc2UgIyBsZWF2ZSB1cyBhIG1lc3NhZ2UgIyBhbmQgd2UnbGwgZ2V0IGJhY2sgdG8geW91IHJpZ2h0IGF3YXkuIiwiZGVmYXVsdF9wcmVjaGF0X21lc3NhZ2UiOiJXZSBjYW4ndCB3YWl0IHRvIHRhbGsgdG8geW91LiBCdXQgZmlyc3QsIHBsZWFzZSB0YWtlIGEgY291cGxlIG9mIG1vbWVudHMgdG8gdGVsbCB1cyBhIGJpdCBhYm91dCB5b3Vyc2VsZi4ifQ==';}</script>
    @endif
</body>
</html>
