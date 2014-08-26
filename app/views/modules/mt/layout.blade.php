@extends('layouts.default')

@section('title')
    @parent :: {{ trans('Marketing') }}
@stop

@section('logo')
@stop

@section('styles')
    {{ HTML::style('assets/css/marketing.css') }}
@stop

@section('content')
    <div class="btn-group-vertical col-md-3">
        <a class="btn btn-default" href="#">{{ trans('mt.consumer.list') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.groups.index') }}">{{ trans('mt.group.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.campaigns.index') }}">{{ trans('mt.campaign.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.sms.index') }}">{{ trans('mt.sms.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.templates.index') }}">{{ trans('mt.template.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.settings.index') }}">{{ trans('mt.setting') }}</a>
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
