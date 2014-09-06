@extends('modules.fd.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('fd.service.list') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::open(['route' => 'fd.services.store', 'class' => 'form-horizontal', ]) }}
        @foreach ([
            'name'         => trans('common.name'),
            'length'       => trans('fd.service.length'),
            'price'        => trans('fd.service.price'),
            'category_id'  => trans('fd.service.category'),
            'sms_confirmation'      => trans('fd.service.sms_confirmation'),
            'account_owner'         => trans('fd.service.account_owner'),
            'bank_account_number'   => trans('fd.service.bank_account_number'),
        ] as $key => $value)
        <div class="form-group">
            <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
            <div class="col-sm-8">
            @if ($key === 'category_id')
                {{ Form::select('category_id'
                   , array('' => trans('fd.service.select_category')) + $categories->lists('name', 'id')
                   , null
                   , array('class' => 'form-control')) }}
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
                {{ Form::submit(trans('fd.service.create'), ['class' => 'btn btn-primary', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@stop
