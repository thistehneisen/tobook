@extends('modules.mt.layout')

@section('top-buttons')
<a href="{{ URL::route('mt.templates.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.template.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.created_at') }}</th>
                <th>{{ trans('common.edit') }}</th>
                <th>{{ trans('common.delete') }}</th>                 
            </tr>
        </thead>
        <tbody>
            @foreach ($templates as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    {{ $value->created_at }}
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('mt.templates.edit', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-info" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.show') }}
                        </button>
                    </a>
                </td>
                <td>
                    {{ Form::open(['route' => ['mt.templates.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#js-confirmDeleteModal">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $templates->links() }}</div>
    
</div>
@section('scripts')
    {{ HTML::script('assets/js/mt/common.js') }}
@stop

@stop
