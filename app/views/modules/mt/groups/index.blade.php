@extends('modules.mt.layout')

@section('top-buttons')
<a href="{{ URL::route('mt.groups.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<!-- <button class="btn btn-default btn-danger js-deleteTemplate"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button> -->
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.group.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $key => $value)
            <tr>
                <!-- <td>
                    <input type="checkbox" id="chkTemplateId" value="{{ $value->id }}" />
                </td> -->
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('mt.groups.edit', ['id' => $value->id]) }}">
                        {{ $value->name }}
                    </a>
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
