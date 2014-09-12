@extends('modules.mt.layout')

@section('top-buttons')
<a href="{{ URL::route('mt.campaigns.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<!-- <button class="btn btn-default btn-danger js-deleteCampaign"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button> -->
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.campaign.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('mt.campaign.subject') }}</th>
                <th>{{ trans('mt.campaign.from_email') }}</th>
                <th>{{ trans('mt.campaign.from_name') }}</th>
                <th>{{ trans('common.status') }}</th>
                <th></th>
                <th>{{ trans('common.edit') }}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $key => $value)
            <tr>
                <!-- <td>
                    <input type="checkbox" id="chkCampaignId" value="{{ $value->id }}" />
                </td> -->
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->subject }}
                </td>
                <td>
                    {{ $value->from_email }}
                </td>
                <td>
                    {{ $value->from_name }}
                </td>
                <td>
                    {{ $value->status }}
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" id="btn-duplication">{{ trans('mt.campaign.duplication') }}</button>
                    @if ($value->status === 'SENT')
                    <button class="btn btn-primary btn-sm" id="btn-statistics" data="">{{ trans('mt.campaign.statistics') }}</button>
                    @endif
                    <input type="hidden" id="campaign_id" value="{{ $value->id }}">
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('mt.campaigns.edit', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.show') }}
                        </button>
                    </a>
                </td>
                <td>
                    {{ Form::open(['route' => ['mt.campaigns.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>                
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $campaigns->links() }}</div>
    
    <!-- Modal -->
    <div class="modal fade" id="campaignsModal" tabindex="-1" role="dialog" aria-labelledby="campaignsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="campaignsModalLabel">{{ trans('mt.campaign.subject') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-3 control-label col-sm-offset-1 text-right">
                                {{ trans('mt.campaign.subject') }}
                            </label>
                            <div class="col-md-7 text-left">
                                <input type="text" id="subject" class="form-control"/>
                            </div>
                            <input type="hidden" id="duplicate_campaign_id"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                    <button type="button" class="btn btn-primary" id="btn-duplicate-campaign">{{ trans('mt.campaign.duplication') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="statisticsModal" tabindex="-1" role="dialog" aria-labelledby="statisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="campaignsModalLabel">{{ trans('mt.campaign.statistics') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <table class="table" id="tblStatistics">
                                    <thead>
                                        <tr>
                                            <th>Clicks</th>
                                            <th>Opens</th>
                                            <th>Rejects</th>
                                            <th>Sent</th>
                                            <th>Unique Clicks</th>
                                            <th>Unique Opens</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>    
    
</div>

@section('scripts')
    {{ HTML::script('assets/js/mt/common.js') }}
    {{ HTML::script('assets/js/mt/campaigns.js') }}
@stop

@stop
