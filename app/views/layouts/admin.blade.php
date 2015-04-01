@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('common.admin') }}
@stop

@section('nav-admin')
<nav class="navbar navbar-default" role="navigation" style="border-radius: 0;">
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
            <ul class="nav navbar-nav">
                <li><a href="{{ route('admin.create') }}"><i class="fa fa-plus"></i> {{ trans('admin.nav.admin') }}</a></li>
                <li><a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i> {{ trans('admin.nav.users') }}</a></li>
                <li><a href="{{ route('admin.master-cats.index') }}"><i class="fa fa-book"></i> {{ trans('admin.nav.master_categories') }}</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bar-chart"></i> {{ trans('admin.nav.stats') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('admin.stats.fd') }}"><i class="fa fa-flash"></i> {{ trans('admin.nav.flash_deals') }}</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('admin.settings') }}"><i class="fa fa-cog"></i> {{ trans('admin.nav.settings') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>
@stop

@section ('footer')
@stop
