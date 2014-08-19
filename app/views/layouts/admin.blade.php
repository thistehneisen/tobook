@extends ('layouts.default')

@section ('title')
    @parent :: {{ trans('common.admin') }}
@stop

@section ('styles')
<style>
    body {padding-top: 50px;}
</style>
@stop

@section ('header')
    <h2 class="comfortaa white">{{ ucfirst(Config::get('admin.prefix')) }}</h2>
@stop

@section ('nav-admin')
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
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
                <li><a href="{{ route('admin.crud.index', ['model' => 'users']) }}">{{ trans('admin.nav.users') }}</a></li>
                <li><a href="{{ route('admin.crud.index', ['model' => 'modules']) }}">{{ trans('admin.nav.modules') }}</a></li>
                <li><a href="{{ route('admin.settings.index') }}">{{ trans('admin.nav.settings') }}</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop

@section ('footer')
@stop
