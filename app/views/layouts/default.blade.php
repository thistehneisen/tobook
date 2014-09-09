<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        ga('send', 'pageview');

    </script>
        @endif
    @show
</head>
<body>
    @section('header')
    <header class="header container-fluid">
        <nav class="main-nav container">
            <a href="{{ route('home') }}" class="logo pull-left">varaa<span>.com</span></a>

            @section('main-nav')
            <div class="pull-right">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-menu">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="main-menu">
                    <ul class="nav navbar-nav">
                    @foreach ($_businessCategories as $category)
                        <li class="dropdown">
                            <a href="{{ route('search') }}?query={{ urlencode($category->name) }}">
                                <i class="fa {{ $category->icon() }}"></i>
                                {{ $category->name }}
                            </a>
                            <ul class="dropdown-menu">
                            @foreach ($category->children as $child)
                                <li><a href="{{ route('search') }}?query={{ urlencode($child->name) }}">{{ $child->name }}</a></li>
                            @endforeach
                            </ul>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            @show
        </nav>

        @section('main-search')
        <div class="search-wrapper row">
            {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline']) }}
                <div class="form-group">
                    <div class="input-group input-group">
                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                        <input type="text" class="form-control" id="js-queryInput" name="query" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('query') }}}" />
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
        </div>
        @show

        @section('sub-nav')
        {{--
        <nav class="sub-nav row">
            <div class="container">
                <div class="pull-left">
                    <i class="fa fa-globe"></i>
                @foreach (Config::get('varaa.languages') as $locale)
                    <a class="language-swicher {{ Config::get('app.locale') === $locale ? 'active' : '' }}" href="{{ UrlHelper::localizeCurrentUrl($locale) }}" title="">{{ strtoupper($locale) }}</a>
                @endforeach
                </div>
                @if (Confide::user())
                    <p class="welcome-text">
                    @if (Session::get('stealthMode') !== null)
                    You're now login as <strong>{{ Confide::user()->username }}</strong>
                    @else {{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!
                    @endif
                    </p>
                @endif

                <ul class="list-inline nav-links pull-right">
                    @if (Confide::user())
                    <li><a href="{{ route('dashboard.index') }}">{{ trans('common.dashboard') }}</a></li>
                    <li><a href="{{ route('user.profile') }}">{{ trans('common.my_account') }}</a></li>
                    @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
                    <li><a href="{{ route('admin.index') }}">{{ trans('common.admin') }}</a></li>
                    @endif
                    <li><a href="{{ route('auth.logout') }}">{{ trans('common.sign_out') }}</a></li>
                    @else
                    <li><a href="{{ route('auth.register') }}">{{ trans('common.register') }}</a></li>
                    <li><a href="{{ route('auth.login') }}">{{ trans('common.sign_in_header') }}</a></li>
                    @endif
                </ul>
            </div>
        </nav>
        --}}
        @show
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
                <p>&copy; {{ date('Y') }} | <a href="#">{{ trans('home.copyright_policy')}}</a></p>
                <ul class="list-unstyled list-inline list-social-networks">
                    <li><a href="{{ Setting::get('facebook-page') }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="{{ Setting::get('google-page') }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="{{ Setting::get('rss-page') }}" target="_blank"><i class="fa fa-rss"></i></a></li>
                    <li><a href="{{ Setting::get('pinterest-page') }}" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                    <li><a href="{{ Setting::get('linkedin-page') }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>
    @show

    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
    @yield('scripts')
</body>
</html>
