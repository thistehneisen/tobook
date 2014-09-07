@extends('modules.lc.layout')

@section('top-buttons')
<a href="{{ URL::route('lc.consumers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('scripts')
    {{ HTML::script('assets/js/loyalty.js') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumer_list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!--<th><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>-->
                <th>{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.consumer') }}</th>
                <th>{{ trans('co.email') }}</th>
                <th>{{ trans('co.phone') }}</th>
                <th>{{ trans('loyalty-card.last_visited') }}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <!--<td>
                    <input type="checkbox" id="chkConsumerId" value="{{ $value->id }}" />
                </td>-->
                <td>{{ $key+1 }}</td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->getNameAttribute() }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->email }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->phone }}</a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.consumers.edit', ['id' => $value->id]) }}">{{ $value->consumer->updated_at }}</a>
                </td>
                <td>
                    {{ Form::open(['route' => ['lc.consumers.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $consumers->links() }}</div>
</div>
@stop
