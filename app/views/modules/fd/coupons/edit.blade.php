@extends('modules.fd.layout')

@section('styles')
    {{ HTML::style('assets/css/datepicker.css') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.coupon.label') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($coupon, array('route' => array('fd.coupons.update', $coupon->id), 'method' => 'PUT', 'class' => 'form-horizontal', )) }}
        @foreach ([
            'service_id'         => trans('common.name'),
            'discounted_price'   => trans('fd.discounted_price'),
            'start_date'         => trans('common.start_date'),
            'end_date'           => trans('common.end_date'),
            'quantity'           => trans('common.quantity'),
        ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-8">
                    @if ($key === 'service_id')
                        {{ Form::select('service_id'
                           , array('' => trans('fd.select_service')) + $services->lists('name', 'id')
                           , null
                           , array('class' => 'form-control')) }}
                    @elseif ($key === 'created_at' || $key === 'updated_at')
                        {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}                   
                    @else
                        {{ Form::text($key, null, ['class' => 'form-control']) }}
                    @endif
                </div>
                <div class="col-sm-2">
                    {{ $errors->first($key) }}
                </div>
            </div>
        @endforeach
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
                {{ Form::submit(trans('fd.coupon.edit'), ['class' => 'btn btn-primary', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
    @section('scripts')
        <script src="{{ asset('assets/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>    
        <script src="{{ asset('assets/js/fd/coupons.js') }}" type="text/javascript"></script>
    @stop
    
@stop
