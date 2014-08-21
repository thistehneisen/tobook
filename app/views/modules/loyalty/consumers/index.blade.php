@extends('modules.loyalty.layout')

@section('top-buttons')
<a href="{{ URL::route('modules.lc.consumers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<button class="btn btn-default btn-danger js-deleteConsumer"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button>
@stop

@section('scripts')
    {{ HTML::script('assets/js/loyalty.js') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Consumer List') }}</h3>
    </div>
    <table class="table table-striped" id="tblDataList">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>
                <th>No</th>
                <th>Consumer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Last Visited</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <td>
                    <input type="checkbox" id="chkConsumerId" value="{{ $value->id }}" />
                </td>
                <td>{{ $key+1 }}</td>
                <td>
                    <a href="{{ URL::route('modules.lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->first_name }} {{ $value->last_name }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->email }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->phone }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->updated_at }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $consumers->links() }}</div>
</div>
@stop
