@extends('modules.fd.layout')

@section('styles')
    {{ HTML::style('assets/css/datepicker.css') }}
    {{ HTML::style('assets/css/flashdeal.css') }}
    {{ HTML::style('assets/css/jquery.timepicker.css') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.flashdeal.list') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($flash, array('route' => array('fd.flashs.update', $flash->id), 'method' => 'PUT', 'class' => 'form-horizontal', )) }}
        @foreach ([
            'service_id'       => trans('fd.service.label'),
            'discounted_price' => trans('fd.discounted_price'),
            'count'            => trans('common.count'),
            'flash_date'       => trans('fd.flashdeal.date'),
            'start_time'       => trans('fd.flashdeal.start_time'),
            'created_at'       => trans('common.created_at'),
            'updated_at'       => trans('common.updated_at'),
        ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-8">
                @if ($key === 'service_id')
                    {{ Form::select('service_id'
                       , array('' => trans('fd.service.select_category')) + $services->lists('name', 'id')
                       , null
                       , array('class' => 'form-control')) }}
                @elseif ($key === 'flash_date')
                    {{ Form::text($key, null, ['class' => 'form-control readonly', 'readonly' => true, ]) }}
                @elseif ($key === 'start_time')
                    {{ Form::text($key, null, ['class' => 'form-control readonly', 'readonly' => true, ]) }}
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
                {{ Form::submit(trans('fd.flashdeal.edit'), ['class' => 'btn btn-primary', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

    @section('scripts')
        <script src="{{ asset('assets/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>    
        <script src="{{ asset('assets/js/fd/flashs.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/js/jquery.timepicker.js') }}" type="text/javascript"></script>
    @stop

@stop
