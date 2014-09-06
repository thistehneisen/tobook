@extends('modules.fd.layout')

@section('top-buttons')
<a href="{{ URL::route('fd.services.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.service.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('fd.service.length') }}</th>
                <th>{{ trans('fd.service.price') }}</th>
                <th>{{ trans('fd.service.category') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('fd.services.edit', ['id' => $value->id]) }}">
                        {{ $value->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.services.edit', ['id' => $value->id]) }}">
                        {{ $value->length }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.services.edit', ['id' => $value->id]) }}">
                        {{ $value->price }}
                    </a>
                </td>                
                <td>
                    <a href="{{ URL::route('fd.services.edit', ['id' => $value->id]) }}">
                        {{ $value->category->name }}
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
