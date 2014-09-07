@extends('modules.fd.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.coupon.active') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('fd.service.label') }}</th>
                <th>{{ trans('common.price') }}</th>
                <th>{{ trans('fd.discounted_price') }}</th>
                <th>{{ trans('common.start_date') }}</th>
                <th>{{ trans('common.end_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->service->name }}
                </td>
                <td>
                    {{ $value->service->length }}
                </td>
                <td>
                    {{ $value->discounted_price }}
                </td>
                <td>
                    {{ $value->start_date }}
                </td>
                <td>
                    {{ $value->end_date }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
