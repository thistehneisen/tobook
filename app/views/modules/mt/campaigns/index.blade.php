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
                <th>{{ trans('common.created_at') }}</th>
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
                    {{ $value->created_at }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
