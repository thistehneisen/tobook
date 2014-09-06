@extends('modules.fd.layout')

@section('top-buttons')
<a href="{{ URL::route('fd.coupons.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.coupon.list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('fd.service.label') }}</th>
                <th>{{ trans('fd.discounted_price') }}</th>
                <th>{{ trans('common.start_date') }}</th>
                <th>{{ trans('common.end_date') }}</th>
                <th>{{ trans('common.quantity') }}</th>
                <th>{{ trans('common.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    <a href="{{ URL::route('fd.coupons.edit', ['id' => $value->id]) }}">
                        {{ $value->service->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.coupons.edit', ['id' => $value->id]) }}">
                        {{ $value->discounted_price }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.coupons.edit', ['id' => $value->id]) }}">
                        {{ $value->start_date }}
                    </a>
                </td>                
                <td>
                    <a href="{{ URL::route('fd.coupons.edit', ['id' => $value->id]) }}">
                        {{ $value->end_date }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('fd.coupons.edit', ['id' => $value->id]) }}">
                        {{ $value->quantity }}
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
