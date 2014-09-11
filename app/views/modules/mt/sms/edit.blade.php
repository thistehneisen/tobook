@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.sms.list') }}</h3>
    </div>
    <div class="panel-body">
        {{ Form::model($sms, array('route' => array('mt.sms.update', $sms->id), 'method' => 'PUT', 'class' => 'form-horizontal')) }}
        @foreach ([
            'title'            => trans('common.title'),
            'content'          => trans('common.content'),
            'created_at'       => trans('common.created_at'),
            'updated_at'       => trans('common.updated_at'),                
        ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-md-7">
                @if ($key === 'content')
                    {{ Form::textarea($key, null, ['class' => 'form-control', 'rows' => 2, 'maxlength' => 160, ]) }}
                @elseif ($key === 'created_at' || $key === 'updated_at')
                    {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}                        
                @else
                    {{ Form::text($key, null, ['class' => 'form-control', 'maxlength' => '64', ]) }}
                @endif
                </div>
                <div class="col-md-3">
                    {{ $errors->first($key) }}
                </div>
            </div>
        @endforeach
        <div class="form-group">
            <div class="col-sm-offset-5 col-sm-10">
                {{ Form::submit(trans('mt.sms.edit'), ['class' => 'btn btn-primary', ]) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@stop
