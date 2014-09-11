@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.consumers') }}</h3>
    </div><br>
    {{ Form::model($consumer->consumer, ['route' => ['lc.consumers.update', $consumer->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
    @foreach ([
        'first_name'    => trans('co.first_name'),
        'last_name'     => trans('co.last_name'),
        'email'         => trans('co.email'),
        'phone'         => trans('co.phone'),
        'address'       => trans('co.address'),
        'postcode'      => trans('co.postcode'),
        'city'          => trans('co.city'),
        'country'       => trans('co.country'),
        'created_at'    => trans('loyalty-card.created_at'),
        'updated_at'    => trans('loyalty-card.updated_at'),
    ] as $key => $value)
    <div class="form-group">
        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-6">
            @if ($key === 'created_at' || $key === 'updated_at')
                {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
            @else
                {{ Form::text($key, Input::old($key), ['class' => 'form-control']) }}
            @endif
        </div>
        <div class="col-sm-3">
            {{ $errors->first($key) }}
        </div>
    </div>
    @endforeach
    <span class="col-sm-offset-4">{{ Form::submit(trans('loyalty-card.finish'), ['class' => 'btn btn-primary']) }}</span>
    {{ Form::close() }}
</div>
@stop
