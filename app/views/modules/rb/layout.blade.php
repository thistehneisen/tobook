@extends ('layouts.dashboard')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
    <style>
    .main { margin: 0 auto; }
    </style>
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
    {{ HTML::script(asset('assets/js/rb/main.js')) }}
@stop

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
                <li><a href="{{ route('rb.bookings.index') }}"><i class="fa fa-bookmark"></i>{{ trans('rb.bookings.index') }}</a></li>
                <li><a href="{{ route('rb.services.index') }}"><i class="fa fa-gift"></i>{{ trans('rb.services.index') }}</a></li>
                <li><a href="{{ route('rb.tables.index') }}"><i class="fa fa-slideshare"></i>{{ trans('rb.tables.index') }}</a></li>
                <li><a href="{{ route('rb.menus.index') }}"><i class="fa fa-cutlery"></i> {{ trans('rb.menus.index') }}</a></li>
                <li><a href="{{ route('rb.groups.index') }}"><i class="fa fa-users"></i> {{ trans('rb.groups.index') }}</a></li>
                <li>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('as.services.index') }}">
                        <i class="fa fa-wrench"></i> {{ trans('as.options.heading') }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('rb.options', ['page' => 'general']) }}">{{ trans('as.options.general.index') }}</a></li>
                        <li><a href="{{ route('rb.options', ['page' => 'working-time']) }}">{{ trans('as.options.working_time.index') }}</a></li>
                        <li><a href="{{ route('rb.options', ['page' => 'booking']) }}">{{ trans('as.options.booking.index') }}</a></li>
                        <li><a href="{{ route('rb.options', ['page' => 'style']) }}">{{ trans('as.options.style.index') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
