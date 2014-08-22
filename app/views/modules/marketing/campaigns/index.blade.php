@extends('modules.marketing.layout')

@section('top-buttons')
<a href="{{ URL::route('modules.mt.campaigns.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<button class="btn btn-default btn-danger js-deleteCampaign"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Campaign List') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll" /></th>
                <th>No</th>
                <th>Subject</th>
                <th>From Email</th>
                <th>From Name</th>
                <th>Status</th>
                <th>Statistics</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $key => $value)
            <tr>
                <td>
                    <input type="checkbox" id="chkCampaignId" value="{{ $value->id }}" />
                </td>
                <td>{{ $key }}</td>
                <td>
                    <a href="{{ URL::to('campaigns/'.$value->id.'/show') }}">{{ $value->subject }}</a>
                </td>
                <td>
                    <a href="{{ URL::to('campaigns/'.$value->id.'/show') }}">{{ $value->from_email }}</a>
                </td>
                <td>
                    <a href="{{ URL::to('campaigns/'.$value->id.'/show') }}">{{ $value->from_name }}</a>
                </td>
                <td>
                    <a href="{{ URL::to('campaigns/'.$value->id.'/show') }}">{{ $value->status }}</a>
                </td>
                <td>
                    STAT INFO
                </td>
                <td>
                    <a href="{{ URL::to('campaigns/'.$value->id.'/show') }}">{{ $value->created_at }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
