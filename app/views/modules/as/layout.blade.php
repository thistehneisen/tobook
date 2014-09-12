@extends ('layouts.dashboard')

@section('logo')
@stop

@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css') }}
    {{ HTML::style(asset('packages/bootstrap-spinner/bootstrap-spinner.min.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
    {{ HTML::style(asset('assets/css/as/appointment.css')) }}
@stop

@section ('scripts')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    {{ HTML::script(asset('packages/bootstrap-spinner/bootstrap-spinner.min.js')) }}
    {{ HTML::script(asset('assets/js/appointment.js')) }}
@stop

@section('main-classes') container as-wrapper @stop

@section ('nav-admin')
<nav class="navbar as-main-nav" role="navigation">
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
            <ul class="nav navbar-nav nav-admin nav-as">
                <li @if (!Request::segment(2)) {{ 'class="active"' }} @endif><a href="{{ route('as.index') }}"><i class="fa fa-calendar"></i> Kalenteri</a></li>
                <li @if (Request::segment(2) === 'bookings') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-bookmark"></i> Varaukset <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.bookings.index') }}">Varaukset</a></li>
                        <li><a href="{{ route('as.bookings.upsert') }}">Tee varaus</a></li>
                        <li><a href="{{ route('as.bookings.invoices') }}">Laskut</a></li>
                        <li><a href="{{ route('as.bookings.customers') }}">Asiakkaat</a></li>
                        <li><a href="{{ route('as.bookings.statistics') }}">Statistiikka</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === 'services') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-gift"></i> Palvelut <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.services.index') }}">{{ trans('as.services.all') }}</a></li>
                        <li><a href="{{ route('as.services.upsert') }}">{{ trans('as.services.add') }}</a></li>
                        <li><a href="{{ route('as.services.categories.index') }}">{{ trans('as.services.categories.all') }}</a></li>
                        <li><a href="{{ route('as.services.resources.index') }}">{{ trans('as.services.resources.all') }}</a></li>
                        <li><a href="{{ route('as.services.extras.index') }}">Lisää lisäpalvelu</a></li>
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
                        <li><a href="{{ route('as.employees.customTime') }}">Työvuorosuunnittelu</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-wrench"></i> Asetukset <span class="caret"></span>
                    </a>
					<ul class="dropdown-menu" role="menu">
					    <li><a href="{{ route('as.options', ['page' => 'general']) }}">{{ trans('as.options.general.index') }}</a></li>
					    <li><a href="{{ route('as.options', ['page' => 'working-time']) }}">{{ trans('as.options.working_time.index') }}</a></li>
                        <li><a href="{{ route('as.options', ['page' => 'booking']) }}">{{ trans('as.options.booking.index') }}</a></li>
					    <li><a href="{{ route('as.options', ['page' => 'style']) }}">{{ trans('as.options.style.index') }}</a></li>
					</ul>
                </li>
                <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-line-chart"></i> {{ trans('as.reports.index') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.reports.employees') }}">{{ trans('as.reports.employees') }}</a></li>
                        <li><a href="#">{{ trans('as.reports.services') }}</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === 'embed') {{ 'class="active"' }} @endif><a href="{{ route('as.embed.index') }}"><i class="fa fa-arrow-down"></i> {{ trans('as.embed.embed') }}</a></li>
                <li><a href="{{ route('as.embed.preview') }}" target="_blank"><i class="fa fa-desktop"></i> {{ trans('as.embed.preview') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
