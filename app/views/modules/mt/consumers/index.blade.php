@extends('modules.mt.layout')

@section('top-buttons')
<button class="btn btn-default btn-success" id="btn-show-group-name"><span class="glyphicon glyphicon-plus"></span> {{ trans('mt.group.create') }}</button>
<button class="btn btn-default btn-info" id="btn-show-campaign-list"><span class="glyphicon glyphicon-plus"></span> {{ trans('mt.campaign.send') }}</button>
<button class="btn btn-default btn-info" id="btn-show-sms-list"><span class="glyphicon glyphicon-plus"></span> {{ trans('mt.sms.send') }}</button>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.consumer.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.email') }}</th>
                <th>{{ trans('common.phone') }}</th>
                <th>{{ trans('common.address') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <td>
                    <input type="checkbox" id="chkConsumerId" value="{{ $value->id }}" />
                </td>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->first_name." ".$value->last_name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->email}}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->phone }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->address }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->created_at }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="groupsModal" tabindex="-1" role="dialog" aria-labelledby="groupsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="groupsModalLabel">{{ trans('mt.group.label') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label col-sm-offset-1 text-right">
                                {{ trans('mt.group.label') }}
                            </label>
                            <div class="col-md-7 text-left">
                                <input type="text" id="group_name" class="form-control"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-create-group">{{ trans('mt.group.create') }}</button>
                </div>
            </div>
        </div>
    </div>
    
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
    <script src="{{ asset('assets/js/mt/consumers.js') }}" type="text/javascript"></script>
@stop

@stop
