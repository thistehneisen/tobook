@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.consumer.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!-- <th><input type="checkbox" id="checkAll" /></th> -->
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.email') }}</th>
                <th>{{ trans('common.phone') }}</th>
                <th>{{ trans('common.address') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($consumers as $key => $value)
            <tr>
                <!-- <td>
                    <input type="checkbox" id="chkConsumerId" value="{{ $value->id }}" />
                </td> -->
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->first_name." ".$value->last_name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->email}}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->phone }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->address }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('mt.consumers.show', ['id' => $value->id]) }}">
                        {{ $value->created_at }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
