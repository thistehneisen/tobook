@extends ('layouts.dashboard')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/alertify/alertify.core.css')) }}
    {{ HTML::style(asset('packages/alertify/alertify.bootstrap.css')) }}
<style>
.pagination { margin: 0 !important; }
</style>
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/alertify/alertify.min.js')) }}
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
                <li><a href="{{ route('consumers.index') }}"><i class="fa fa-group"></i> {{ trans('loyalty-card.consumer_management') }}</a></li>
                <li><a href="{{ route('offers.index') }}"><i class="fa fa-gift"></i> {{ trans('loyalty-card.offers') }}</a></li>
                <li><a href="{{ route('vouchers.index') }}"><i class="fa fa-money"></i> {{ trans('loyalty-card.vouchers') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
