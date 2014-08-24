@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumer_management') }}</h3>
    </div><br>
    {{ Form::open(['route' => 'lc.consumers.store', 'class' => 'form-horizontal']) }}
    @foreach ([
        'first_name'    => trans('co.first_name'),
        'last_name'     => trans('co.last_name'),
        'email'         => trans('co.email'),
        'phone'         => trans('co.phone'),
        'address'       => trans('co.address'),
        'postcode'      => trans('co.postcode'),
        'city'          => trans('co.city'),
        'country'       => trans('co.country'),
    ] as $key => $value)
    <div class=" form-group">
        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-5">
            {{ Form::text($key, Input::old($key), ['class' => 'form-control']) }}
        </div>
        <div class="col-sm-3">
            {{ $errors->first($key) }}
        </div>
    </div>
    @endforeach
    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-10">
            {{ Form::submit(trans('loyalty-card.finish'), array('class' => 'btn btn-primary')) }}
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop
