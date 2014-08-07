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
    {{ HTML::style(asset('assets/css/style.css')) }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
        <header class="header {{ Request::is('/') ? 'homepage' : '' }}">
        @section('nav')
            <nav class="text-right">
                <ul class="list-inline nav-links">
                    <li><a href="#">Etusivu</a></li>
                    <li><a href="#">Rekisteröidy</a></li>
                    <li><a href="{{ route('auth.login') }}">Kirjaudu</a></li>
                </ul>
            </nav>
        @show
        
        <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a>
        @yield('header')
        </header>
        
        <main role="main" class="container main">
        @yield('content')
        </main>

        <hr class="grey-divider">

        <footer class="container footer">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <h4>Copyright</h4>
                    <p class="company-name"><span>varaa</span>.com</p>
                    <p>&copy; {{ date('Y') }} | <a href="#">Privacy Policy</a></p>
                    <ul class="list-unstyled list-inline list-social-networks">
                        <li><a href="" target="_blank"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="" target="_blank"><i class="fa fa-rss"></i></a></li>
                        <li><a href="" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                        <li><a href="" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-lg-4">
                    <h4>Newsletter</h4>
                    {{ Form::open() }}
                    <div class="form-group">
                        <input type="email" placeholder="Enter Your Email" class="form-control">
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-danger">SUBMIT</button>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="col-md-4 col-lg-4">
                    <h4>Sijaitsemme</h4>
                    <p>Kaupattie 8, Helsinki</p>
                    <dl class="dl-horizontal">
                        <dt>Freephone</dt>
                        <dd>+1 800 559 6580</dd>
                        <dt>Telephone</dt>
                        <dd>+1 800 603 6035</dd>
                        <dt>Fax</dt>
                        <dd>+1 800 889 9898</dd>
                    </dl>
                </div>
            </div>
        </footer>

    {{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
    {{ HTML::script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js') }}
    @yield('scripts')
</body>
</html>
