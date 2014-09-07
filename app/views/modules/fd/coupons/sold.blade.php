@extends('modules.fd.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.coupon.sold') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('common.consumer') }}</th>
                <th>{{ trans('fd.service.label') }}</th>
                <th>{{ trans('fd.discounted_price') }}</th>
                <th>{{ trans('common.start_time') }}</th>
                <th>{{ trans('common.end_time') }}</th>
                <th>{{ trans('fd.coupon.code') }}</th>
                <th>{{ trans('fd.coupon.used') }}</th>
                <th>{{ trans('fd.coupon.money_received') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->consumer->first_name." ".$value->consumer->last_name }}
                </td>
                <td>
                    {{ $value->coupon->service->name }}
                </td>
                <td>
                    {{ $value->coupon->discounted_price }}
                </td>
                <td>
                    {{ $value->coupon->start_date }}
                </td>
                <td>
                    {{ $value->coupon->end_date }}
                </td>                
                <td>
                    {{ $value->coupon_code }}
                </td>
                <td>
                    {{ ($value->is_used) ? "Yes" : "No"; }}
                </td>
                <td>
                    {{ ($value->is_paid) ? "Yes" : "No"; }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
