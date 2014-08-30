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

        <!-- Modal Dialog -->
        <div class="modal fade" id="js-confirmDeleteModal" role="dialog" aria-labelledby="js-confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{{ trans('loyalty-card.delete_confirmation') }}</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ trans('loyalty-card.delete_question') }}</p>
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
