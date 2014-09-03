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
                <th>{{ trans('common.statistics') }}</th>
                <th></th>
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
                    <a href="{{ URL::route('mt.campaigns.edit', ['id' => $value->id]) }}">
                        {{ $value->subject }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.campaigns.edit', ['id' => $value->id]) }}">
                        {{ $value->from_email }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.campaigns.edit', ['id' => $value->id]) }}">
                        {{ $value->from_name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.campaigns.edit', ['id' => $value->id]) }}">
                        {{ $value->status }}
                    </a>
                </td>
                <td>
                    STAT INFO
                </td>
                <td>
                    <button class="btn btn-info btn-xs" id="btn-duplication">Duplication</button>
                    <input type="hidden" id="campaign_id" value="{{ $value->id }}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
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
    
</div>

@section('scripts')
    <script src="{{ asset('assets/js/mt/campaigns.js') }}" type="text/javascript"></script>
@stop

@stop
