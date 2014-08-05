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
    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('styles')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
        <header class="header homepage">
            <nav class="text-right">
                <ul class="list-inline nav-links">
                    <li><a href="#">Etusivu</a></li>
                    <li><a href="#">Rekisteröidy</a></li>
                    <li><a href="#">Kirjaudu</a></li>
                </ul>
            </nav>

            <a href="#"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a>
            <p><img src="{{ asset('assets/img/homepage-text.png') }}" alt="" class="img-homepage"></p>
            <p><a href="#"><img src="{{ asset('assets/img/btn-aloita-nyt.jpg') }}" alt=""></a></p>
        </header>
        
        <main role="main" class="container main">
            <div class="row services">
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconHome.png') }}" alt=""></p>
                        <p>Kotisivut</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconLoyality.png') }}" alt=""></p>
                        <p>Kantiskortti</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconAppointment.png') }}" alt=""></p>
                        <p>Ajanvaraus</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconCustomer.png') }}" alt=""></p>
                        <p>Asiakasrekisteri</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconCashier.png') }}" alt=""></p>
                        <p>Kassa</p>
                    </a>
                </div>
                <div class="col-md-2 col-lg-2">
                    <a href="">
                        <p><img src="{{ asset('assets/img/iconMarketing.png') }}" alt=""></p>
                        <p>Markkinointityokalut</p>
                    </a>
                </div>
            </div>
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

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>
