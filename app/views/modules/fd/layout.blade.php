@extends('layouts.default')

@section('title')
    @parent :: {{ trans('common.flash_deal') }}
@stop

@section('logo')
@stop

@section('styles')
    {{ HTML::style('assets/css/flashdeal.css') }}
@stop

@section('content')
    <div class="btn-group-vertical col-md-3">
        <a class="btn btn-default" href="{{ URL::route('fd.services.index') }}">{{ trans('fd.service.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('fd.flashs.index') }}">{{ trans('fd.flashdeal.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('fd.coupons.index') }}">{{ trans('fd.coupon.management') }}</a>
        <a class="btn btn-default" href="#">{{ trans('common.settings') }}</a>
        <a class="btn btn-default" href="#">{{ trans('fd.flashdeal.sold') }}</a>
        <a class="btn btn-default" href="#">{{ trans('fd.coupon.sold') }}</a>
        <a class="btn btn-default" href="#">{{ trans('fd.flashdeal.active') }}</a>
        <a class="btn btn-default" href="#">{{ trans('fd.coupon.active') }}</a>
        <a class="btn btn-default" href="#">{{ trans('fd.expired') }}</a>
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
