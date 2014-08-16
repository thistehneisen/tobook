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

    {{ HTML::style('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css') }}
    {{ HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css') }}
    @yield('styles')

    {{-- Temporary solution: Increment the version number to force clear cache --}}
    {{ HTML::style(asset('assets/css/main.css?v=00001')) }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <header class="header">
        @section('nav')
        <nav class="text-right">
            @if (Confide::user())
                <p class="welcome-text">
                @if (Session::get('stealthMode') !== null)
                You're now login as <strong>{{ Confide::user()->username }}</strong>
                @else
                {{ trans('common.welcome') }}, <strong>{{ Confide::user()->username }}</strong>!
                @endif
                </p>
            @endif

            <ul class="list-inline nav-links">
                <li><a href="{{ route('home') }}">{{ trans('common.homepage') }}</a></li>
                @if (Confide::user())
                <li><a href="{{ route('dashboard.index') }}">{{ trans('common.dashboard') }}</a></li>
                <li><a href="{{ route('user.profile') }}">{{ trans('common.my_account') }}</a></li>
                @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
                <li><a href="{{ route('admin.index') }}">{{ trans('common.admin') }}</a></li>
                @endif
                {{-- <li><a href="">{{ trans('common.help') }}</a></li> --}}
                <li><a href="{{ route('auth.logout') }}">{{ trans('common.sign_out') }}</a></li>
                @else
                <li><a href="{{ route('auth.register') }}">{{ trans('common.register') }}</a></li>
                <li><a href="{{ route('auth.login') }}">{{ trans('common.sign_in_header') }}</a></li>
                @endif
            </ul>
        </nav>
        @show

        <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}" alt="{{ trans('common.site_name') }}" class="logo"></a>
        @yield('header')
        @yield('subheader')
    </header>

    @yield('nav-admin')

    <main role="main" class="container main">
        @yield('content')
    </main>

    @yield('iframe')

    @section ('footer')
    <hr class="grey-divider">
    <footer class="container footer">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <h4>{{ trans('home.copyright') }}</h4>
                <p class="company-name"><span>varaa</span>.com</p>
                <p>&copy; {{ date('Y') }} | <a href="#">{{ trans('home.copyright_policy')}}</a></p>
                <ul class="list-unstyled list-inline list-social-networks">
                    <li><a href="" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    <li><a href="" target="_blank"><i class="fa fa-rss"></i></a></li>
                    <li><a href="" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                    <li><a href="" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>
            <div class="col-md-4 col-lg-4">
                <h4>{{ trans('home.newsletter') }}</h4>
                {{ Form::open() }}
                <div class="form-group">
                    <input type="email" placeholder="{{ trans('home.enter_your_email') }}" class="form-control">
                </div>
                <div class="form-group text-right">
                    <button class="btn btn-danger">{{ trans('home.submit') }}</button>
                </div>
                {{ Form::close() }}
            </div>
            <div class="col-md-4 col-lg-4">
                <h4>{{ trans('home.location') }}</h4>
                <p>Kaupattie 8, Helsinki</p>
                <dl class="dl-horizontal">
                    <dt>{{ trans('home.freephone') }}</dt>
                    <dd>+1 800 559 6580</dd>
                    <dt>{{ trans('home.telephone') }}</dt>
                    <dd>+1 800 603 6035</dd>
                    <dt>{{ trans('home.fax') }}</dt>
                    <dd>+1 800 889 9898</dd>
                </dl>
            </div>
        </div>
    </footer>
    @show

    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
    @yield('scripts')
</body>
</html>
