@extends ('layouts.dashboard')

@section ('scripts')
    @parent
    {{ HTML::script(asset_path('co/scripts/main.js')) }}
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
                        <i class="fa fa-user"></i>
                        {{ trans('co.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.index')}}">{{ trans('co.all') }}</a></li>
                        @if (Entrust::hasRole('Admin') || session_get('stealthMode') !== null)
                            <li><a href="{{ route('consumer-hub.import')}}">{{ trans('co.import.import') }}</a></li>
                        @endif
                    </ul>
                </li>
                <li><a href="{{ route('consumer-hub.groups.index')}}"><i class="fa fa-users"></i> {{ trans('co.groups.all') }}</a></li>
                {{-- Only display these two options for admin on tobook --}}
                @if (App::environment() !== 'tobook' || Entrust::hasRole('Admin') || session_get('stealthMode') !== null)
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('consumer-hub.email_templates.index') }}">
                        <i class="fa fa-envelope"></i>
                        {{ trans('co.email_templates.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.email_templates.index')}}">{{ trans('co.email_templates.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.email_templates.upsert')}}">{{ trans('co.email_templates.add') }}</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('consumer-hub.sms_templates.index') }}">
                        <i class="fa fa-mobile"></i>
                        {{ trans('co.sms_templates.all') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.sms_templates.index')}}">{{ trans('co.sms_templates.all') }}</a></li>
                        <li><a href="{{ route('consumer-hub.sms_templates.upsert')}}">{{ trans('co.sms_templates.add') }}</a></li>
                    </ul>
                </li>
                @endif
                {{-- End: Only display these two options for admin on tobook --}}
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-history"></i>
                        {{ trans('co.history.index') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('consumer-hub.history.email')}}">{{ trans('co.history.email') }}</a></li>
                        <li><a href="{{ route('consumer-hub.history.sms')}}">{{ trans('co.history.sms') }}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
