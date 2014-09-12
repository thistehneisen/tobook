@extends('modules.mt.layout')

@section('top-buttons')
<button class="btn btn-default btn-info" id="btn-show-campaign-list"><span class="glyphicon glyphicon-envelope"></span> {{ trans('mt.campaign.send') }}</button>
<button class="btn btn-default btn-info" id="btn-show-sms-list"><span class="glyphicon glyphicon-comment"></span> {{ trans('mt.sms.send') }}</button>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.group.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.created_at') }}</th>
                <th>{{ trans('common.edit') }}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $key => $value)
            <tr>
                <td>
                    <input type="checkbox" id="js-chkGroupId" value="{{ $value->id }}" />
                </td>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    {{ $value->created_at }}
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('mt.groups.edit', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.show') }}
                        </button>
                    </a>                    
                </td>
                <td>
                    {{ Form::open(['route' => ['mt.groups.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $groups->links() }}</div>
    
    <input type="hidden" id="link-campaigns-group" value="{{ URL::route('mt.campaigns.group') }}"/>
    <input type="hidden" id="link-sms-group" value="{{ URL::route('mt.sms.group') }}"/>    
    
    <div class="modal fade" id="campaignsModal" tabindex="-1" role="dialog" aria-labelledby="campaignsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="campaignsModalLabel">{{ trans('mt.campaign.list') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label col-sm-offset-1 text-right">
                                {{ trans('mt.campaign.label') }}
                            </label>
                            <div class="col-md-7 text-left">
                                <select class="form-control" id="campaignId">
                                    <option value="">Please select campaigns.</option>
                                    @foreach ($campaigns as $item)
                                        <option value="{{ $item->id }}">{{ $item->subject }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-send-campaign">{{ trans('mt.campaign.send') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="smsModal" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="smsModalLabel">{{ trans('mt.sms.list') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label col-sm-offset-1 text-right">
                                {{ trans('mt.sms.label') }}
                            </label>
                            <div class="col-md-7 text-left">
                                <select class="form-control" id="smsId">
                                    <option value="">Please select SMS.</option>
                                    @foreach ($sms as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-send-sms">{{ trans('mt.sms.send') }}</button>
                </div>
            </div>
        </div>
    </div>
        
</div>

@section('scripts')
    {{ HTML::script('assets/js/mt/common.js') }}
    {{ HTML::script('assets/js/mt/groups.js') }}
@stop

@stop
