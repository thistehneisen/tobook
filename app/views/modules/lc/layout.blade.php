@extends('layouts.default')

@section('title')
    @parent :: {{ trans('dashboard.loyalty') }}
@stop

@section('logo')
@stop

@section('styles')
    {{ HTML::style('assets/css/loyalty.css') }}
@stop

@section('content')
    <div class="btn-group-vertical col-md-3">
        <a class="btn btn-default" href="{{ URL::route('lc.consumers.index') }}">{{ trans('loyalty-card.consumer_management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('lc.offers.index') }}">{{ trans('loyalty-card.offers') }}</a>
        <a class="btn btn-default" href="{{ URL::route('lc.vouchers.index') }}">{{ trans('loyalty-card.vouchers') }}</a>
    </div>
    <div class="col-md-9">
        <div class="top-buttons pull-right">
            @yield('top-buttons')
        </div>
        <div class="clearfix"></div>

        @yield('sub-content')
    </div>
@stop
