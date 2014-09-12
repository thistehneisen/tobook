@extends('layouts.dashboard')

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
        <a class="btn btn-default" href="{{ URL::route('mt.consumers.index') }}">{{ trans('mt.consumer.list') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.groups.index') }}">{{ trans('mt.group.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.campaigns.index') }}">{{ trans('mt.campaign.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.sms.index') }}">{{ trans('mt.sms.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.templates.index') }}">{{ trans('mt.template.management') }}</a>
        <a class="btn btn-default" href="{{ URL::route('mt.settings.index') }}">{{ trans('mt.setting.label') }}</a>
    </div>
    <div class="col-md-9">
        <div class="top-buttons pull-right">
            @yield('top-buttons')
        </div>
        <div class="clearfix"></div>
        
        <!-- Modal Dialog -->
        <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{{ trans('common.delete_confirmation') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('common.delete_question') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                        <button type="button" class="btn btn-danger" id="confirm">{{ trans('common.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        @yield('sub-content')
    </div>
@stop
@section('scripts')

@stop
