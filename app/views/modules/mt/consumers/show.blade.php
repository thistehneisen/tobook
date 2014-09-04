@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.consumer.detail') }}</h3>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">{{ trans('common.first_name') }}</label>
                <div class="col-sm-4">{{ $consumer->first_name }}</div>
                <label class="col-sm-2 control-label">{{ trans('common.last_name') }}</label>
                <div class="col-sm-4">{{ $consumer->last_name }}</div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">{{ trans('common.email') }}</label>
                <div class="col-sm-4">{{ $consumer->email }}</div>
                <label class="col-sm-2 control-label">{{ trans('common.phone') }}</label>
                <div class="col-sm-4">{{ $consumer->phone }}</div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">{{ trans('common.address') }}</label>
                <div class="col-sm-4">{{ $consumer->address }}</div>
                <label class="col-sm-2 control-label">{{ trans('common.city') }}</label>
                <div class="col-sm-4">{{ $consumer->city }}</div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">{{ trans('common.postcode') }}</label>
                <div class="col-sm-4">{{ $consumer->postcode }}</div>
                <label class="col-sm-2 control-label">{{ trans('common.country') }}</label>
                <div class="col-sm-4">{{ $consumer->country }}</div>
            </div>            
        </div>
        
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">{{ trans('common.created_at') }}</label>
                <div class="col-sm-4">{{ $consumer->created_at }}</div>
                <label class="col-sm-2 control-label">{{ trans('common.updated_at') }}</label>
                <div class="col-sm-4">{{ $consumer->updated_at }}</div>
            </div>            
        </div>        
    </div>
</div>

@stop
