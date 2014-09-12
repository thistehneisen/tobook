@extends('modules.mt.layout')

@section('top-buttons')
<a href="{{ URL::route('mt.sms.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<!-- <button class="btn btn-default btn-danger js-deleteCampaign"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button> -->
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.sms.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.title') }}</th>
                <th>{{ trans('common.status') }}</th>
                <th>{{ trans('common.created_at') }}</th>
                <th>{{ trans('common.edit') }}</th>
                <th>{{ trans('common.delete') }}</th>                
            </tr>
        </thead>
        <tbody>
            @foreach ($sms as $key => $value)
            <tr>
                <!-- <td>
                    <input type="checkbox" id="chkCampaignId" value="{{ $value->id }}" />
                </td> -->
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->title }}
                </td>
                <td>
                    {{ $value->status }}
                </td>
                <td>
                    {{ $value->created_at }}
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('mt.sms.edit', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.show') }}
                        </button>
                    </a>
                </td>
                <td>
                    {{ Form::open(['route' => ['mt.sms.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $sms->links() }}</div>
</div>

@section('scripts')
    {{ HTML::script('assets/js/mt/common.js') }}
@stop

@stop
