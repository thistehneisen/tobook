@extends ('layouts.dashboard')

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
                <li><a href="{{ route('lc.offers.index') }}"><i class="fa fa-gift"></i> {{ trans('loyalty-card.offer.index') }}</a></li>
                <li><a href="{{ route('lc.vouchers.index') }}"><i class="fa fa-money"></i> {{ trans('loyalty-card.voucher.index') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop
