@extends('modules.fd.layout')

@section('top-buttons')
<a href="{{ URL::route('fd.flashs.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.flashdeal.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('fd.service.label') }}</th>
                <th>{{ trans('fd.discounted_price') }}</th>
                <th>{{ trans('common.count') }}</th>
                <th>{{ trans('fd.flashdeal.date') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($flashs as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('fd.flashs.edit', ['id' => $value->id]) }}">
                        {{ $value->service->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.flashs.edit', ['id' => $value->id]) }}">
                        {{ $value->discounted_price }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.flashs.edit', ['id' => $value->id]) }}">
                        {{ $value->count }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.flashs.edit', ['id' => $value->id]) }}">
                        {{ $value->flash_date }}
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
