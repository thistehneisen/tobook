@extends ('layouts.dashboard')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/alertify/css/alertify.min.css')) }}
    {{ HTML::style(asset('packages/alertify/css/themes/default.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('assets/js/modules/co.js')) }}
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
@stop

@section('extra_modals')
<!-- History Modal Dialog -->
<div class="modal fade" id="js-historyModal" role="dialog" aria-labelledby="js-historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('History') }}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
            </div>
        </div>
    </div>
</div>
@stop

@section ('nav-admin')
<nav class="navbar co-main-nav" role="navigation">
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
            <ul class="nav navbar-nav nav-admin nav-co">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('consumer-hub.index') }}">
                        <i class="fa fa-users"></i>
                        {{ trans('co.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.index')}}">{{ trans('co.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.groups.index')}}">{{ trans('co.groups.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.upsert')}}">{{ trans('co.add') }}</a></li>
                        @if (Entrust::hasRole('Admin') || Session::get('stealthMode') !== null)
                            <li><a href="{{ route('consumer-hub.import')}}">{{ trans('co.import.import') }}</a></li>
                        @endif
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('consumer-hub.campaigns.index') }}">
                        <i class="fa fa-rss"></i>
                        {{ trans('co.campaigns.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.campaigns.index')}}">{{ trans('co.campaigns.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.campaigns.upsert')}}">{{ trans('co.campaigns.add') }}</a></li>
                        <li><a href="{{ route('consumer-hub.campaigns.history')}}">{{ trans('co.campaigns.history') }}</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('consumer-hub.sms.index') }}">
                        <i class="fa fa-mobile"></i>
                        {{ trans('co.sms.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.sms.index')}}">{{ trans('co.sms.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.sms.upsert')}}">{{ trans('co.sms.add') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
