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
    <div class="btn-group-vertical col-md-3">
        <a class="btn btn-default" href="{{ URL::to('consumers') }}">Consumer List</a>
        <a class="btn btn-default" href="{{ URL::to('consumers') }}">Stamp List</a>
        <a class="btn btn-default" href="{{ URL::to('consumers') }}">Point List</a>
    </div>
    <div class="col-md-9">
        <div class="top-buttons pull-right">
            @yield('top-buttons')
        </div>
        <div class="clearfix"></div>

        @yield('sub-content')
    </div>
@stop
