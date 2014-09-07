@extends('modules.fd.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.coupon.expired') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('common.no') }}</th>
                <th>{{ trans('fd.service.label') }}</th>
                <th>{{ trans('fd.service.length') }}</th>
                <th>{{ trans('common.price') }}</th>
                <th>{{ trans('fd.discounted_price') }}</th>
                <th>{{ trans('fd.flashdeal.start_time') }}</th>
                <th>{{ trans('fd.flashdeal.end_time') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($flashs as $key => $value)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{ $value->service->name }}
                </td>
                <td>
                    {{ $value->service->length }}
                </td>
                <td>
                    {{ $value->service->price }}
                </td>
                <td>
                    {{ $value->discounted_price }}
                </td>
                <td>
                    {{ $value->flash_date.' '.$value->start_time }}
                </td>
                <td>
                    {{ $value->flash_date.' '.$value->end_time }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
