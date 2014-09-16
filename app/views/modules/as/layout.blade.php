@extends ('layouts.dashboard')

@section('logo')
@stop

@section ('styles')
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css') }}
    {{ HTML::style(asset('packages/bootstrap-spinner/bootstrap-spinner.min.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
    {{ HTML::style(asset('assets/css/as/appointment.css')) }}
<style>
.pagination { margin: 0 !important; }
</style>
@stop

@section ('scripts')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js') }}
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
                <li @if (!Request::segment(2)) {{ 'class="active"' }} @endif><a href="{{ route('as.index') }}"><i class="fa fa-calendar"></i> {{ trans('as.index.calendar') }}</a></li>
                <li @if (Request::segment(2) === 'bookings') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-bookmark"></i> {{ trans('as.bookings.all') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.bookings.index') }}">{{ trans('as.bookings.all') }}</a></li>
                        <li><a href="{{ route('as.bookings.upsert') }}">{{ trans('as.bookings.add') }}</a></li>
                        {{--
                        <li><a href="{{ route('as.bookings.invoices') }}">{{ trans('as.bookings.invoices') }}</a></li>
                        <li><a href="{{ route('as.bookings.customers') }}">{{ trans('as.bookings.customers') }}</a></li>
                        --}}
                        <li><a href="{{ route('as.bookings.statistics') }}">{{ trans('as.bookings.statistics') }}</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === 'services') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-gift"></i> {{ trans('as.services.index') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.services.index') }}">{{ trans('as.services.all') }}</a></li>
                        <li><a href="{{ route('as.services.upsert') }}">{{ trans('as.services.add') }}</a></li>
                        <li><a href="{{ route('as.services.categories.index') }}">{{ trans('as.services.categories.all') }}</a></li>
                        <li><a href="{{ route('as.services.resources.index') }}">{{ trans('as.services.resources.all') }}</a></li>
                        <li><a href="{{ route('as.services.extras.index') }}">{{ trans('as.services.extras.add') }}</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === 'employees') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.employees.index') }}">
                        <i class="fa fa-users"></i> {{ trans('as.employees.all') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('as.employees.index') }}">{{ trans('as.employees.all') }}</a></li>
                        <li><a href="{{ route('as.employees.upsert') }}">{{ trans('as.employees.add') }}</a></li>
                        {{--
                        <li><a href="{{ route('as.employees.freetime') }}">{{ trans('as.employees.free_times') }}</a>
                        --}}
                        <li><a href="{{ route('as.employees.customTime') }}">{{ trans('as.employees.workshift_planning') }}</a></li>
                    </ul>
                </li>
                <li @if (Request::segment(2) === '') {{ 'class="active"' }} @endif class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-wrench"></i> {{ trans('as.options.heading') }} <span class="caret"></span>
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
                        {{--
                        <li><a href="#">{{ trans('as.reports.services') }}</a></li>
                        --}}
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
