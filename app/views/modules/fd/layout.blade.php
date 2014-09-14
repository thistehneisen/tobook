@extends ('layouts.dashboard')

@section ('styles')
    @parent
@stop

@section ('scripts')
    @parent
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
                <li><a href="{{ route('fd.index') }}"><i class="fa fa-dashboard"></i> {{ trans('common.home') }}</a></li>
                <li><a href="{{ route('fd.services.upsert') }}"><i class="fa fa-taxi"></i> {{ trans('fd.nav.add_service') }}</a></li>
                <li><a href="{{ route('fd.index') }}"><i class="fa fa-tags"></i> {{ trans('fd.nav.add_flash_deal') }}</a></li>
                <li><a href="{{ route('fd.index') }}"><i class="fa fa-money"></i> {{ trans('fd.nav.add_coupon') }}</a></li>
                <li><a href="{{ route('fd.index') }}"><i class="fa fa-cog"></i> {{ trans('fd.nav.settings') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
