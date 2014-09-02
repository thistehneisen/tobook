@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.group.list') }}</h3>
    </div><br>
    {{ Form::open(['route' => 'mt.groups.store', 'class' => 'form-horizontal']) }}
    @foreach ([
        'name'         => trans('common.name'),
    ] as $key => $value)
    <div class="form-group">
        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-6">
            {{ Form::text($key, null, ['class' => 'form-control', 'maxlength' => 64]) }}
        </div>
        <div class="col-sm-3">{{ $errors->first($key) }}</div>
    </div>
    @endforeach
    <div class="form-group">
        <div class="col-sm-offset-5 col-sm-10">
            {{ Form::submit(trans('mt.group.create'), ['class' => 'btn btn-primary', ]) }}
        </div>
    </div>
    {{ Form::close() }}
</div>

@stop
