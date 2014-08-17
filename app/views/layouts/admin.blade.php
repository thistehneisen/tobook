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
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ trans('common.users') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('admin.crud.index', ['model' => 'users']) }}">{{ trans('common.users') }}</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('admin.settings.index') }}">{{ trans('common.settings') }}</a></li>
            </ul>
            {{--
            <form class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </form>
            --}}
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
@stop

@section ('footer')
@stop
