@extends('modules.loyalty.layout')

@section('top-buttons')
<a href="{{ URL::to('consumers/create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<button class="btn btn-default btn-danger js-deleteConsumer"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Consumer List') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll" /></th>
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
                <td>{{ $key }}</td>
                <td>
                    <a href="consumerForm.php?id={{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</a>
                </td>
                <td>
                    <a href="consumerForm.php?id={{ $value->id }}">{{ $value->email }}</a>
                </td>
                <td>
                    <a href="consumerForm.php?id={{ $value->id }}">{{ $value->phone }}</a>
                </td>
                <td>
                    <a href="consumerForm.php?id={{ $value->id }}">{{ $value->updated_at }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
