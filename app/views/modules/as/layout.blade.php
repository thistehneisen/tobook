@extends ('layouts.default')

@section('logo')
@stop

@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css') }}
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
@stop

@section ('scripts')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    {{ HTML::script('//code.jquery.com/ui/1.11.1/jquery-ui.js') }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    {{ HTML::script(asset('assets/js/appointment.js')) }}
@stop

@section ('nav-admin')
<nav class="navbar" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin-menu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="admin-menu">
            <ul class="nav navbar-nav nav-admin">
                <li @if (!Request::segment(2)) {{ 'class="active"' }} @endif><a href="{{ route('as.index') }}"><i class="fa fa-calendar"></i> Kalenteri</a></li>
        <li @if (Request::segment(2) === 'bookings') {{ 'class="active"' }} @endif class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                <i class="fa fa-bookmark"></i> Varaukset <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="">Varaukset</a></li>
                <li><a href="">Tee varaus</a></li>
                <li><a href="">Laskut</a></li>
                <li><a href="">Asiakkaat</a></li>
                <li><a href="">Statistiikka</a></li>
            </ul>
        </li>
        <li @if (Request::segment(2) === 'services') {{ 'class="active"' }} @endif class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                <i class="fa fa-cloud"></i> Palvelut <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('as.services.index') }}">Index</a></li>
                <li><a href="{{ route('as.services.create') }}">Lisää palveluita</a></li>
                <li><a href="{{ route('as.services.categories.index') }}">{{ trans('as.services.category.all') }}</a></li>
                <li><a href="{{ route('as.services.resources.index') }}">{{ trans('as.services.resource.all') }}</a></li>
                <li><a href="{{ route('as.services.extras') }}">Lisää lisäpalvelu</a></li>
            </ul>
        </li>
        <li @if (Request::segment(2) === 'employees') {{ 'class="active"' }} @endif class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.employees.index') }}">
                <i class="fa fa-users"></i> Työntekijät <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="{{ route('as.employees.index') }}">{{ trans('as.employees.all') }}</a></li>
                <li><a href="{{ route('as.employees.upsert') }}">{{ trans('as.employees.add') }}</a></li>
                <li><a href="{{ route('as.employees.freetime') }}">Vapaat</a></li>
                <li><a href="{{ route('as.employees.customtime') }}">Työvuorosuunnittelu</a></li>
            </ul>
        </li>
        <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                <i class="fa fa-cog"></i> Asetukset <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="">Yleinen</a></li>
                <li><a href="">Varaukset</a></li>
                <li><a href="">Työajat</a></li>
                <li><a href="">Laskut</a></li>
                <li><a href="">Tyyli</a></li>
            </ul>
        </li>
        <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                <i class="fa fa-signal"></i> Raportit <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="">Työntekijävakko</a></li>
                <li><a href="">Palveluvalikko</a></li>
            </ul>
        </li>
        <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif><a href="#"><i class="fa fa-arrow-down"></i> Asenna</a></li>
        <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif><a href="#"><i class="fa fa-desktop"></i> Esikatselu</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
