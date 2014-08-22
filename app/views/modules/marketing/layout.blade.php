@extends('layouts.default')

@section('title')
    @parent :: {{ trans('common.marketing') }}
@stop

@section('logo')
@stop

@section('styles')
    {{ HTML::style('assets/css/marketing.css') }}
@stop

@section('content')
    <div class="btn-group-vertical col-md-3">
        <a class="btn btn-default" href="#">Consumer List</a>
        <a class="btn btn-default" href="#">Group Management</a>
        <a class="btn btn-default" href="{{ URL::route('modules.mt.campaigns.index') }}">Campaign Management</a>
        <a class="btn btn-default" href="#">SMS Management</a>
        <a class="btn btn-default" href="#">Template Management</a>
        <a class="btn btn-default" href="#">Settings</a>
    </div>
    <div class="col-md-9">
        <div class="top-buttons pull-right">
            @yield('top-buttons')
        </div>
        <div class="clearfix"></div>

        @yield('sub-content')
    </div>
@stop
@section('scripts')

@stop