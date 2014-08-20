@extends('layouts.default')

@section('title')
    @parent :: {{ trans('common.loyalty') }}
@stop

@section('logo')
@stop

@section('styles')
    {{ HTML::style('assets/css/loyalty.css') }}
@stop

@section('content')
    <div class="col-md-3">
        <ul class="list-group sidebar-nav-v1">
            <li class="list-group-item active"><a href="consumerList.php">Consumer List</a></li>
            <li class="list-group-item"><a href="stampList.php">Stamp List</a></li>
            <li class="list-group-item"><a href="pointList.php">Point List</a></li>
        </ul>
    </div>
    <div class="col-md-9">
        @yield('sub-content');
    </div>
@stop
