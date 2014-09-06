@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.offers') }}</h3>
    </div><br>
    {{ Form::model($offer, ['route' => ['lc.offers.update', $offer->id], 'method' => 'PUT', 'class' => 'form-horizontal']) }}
    <div class="form-group">
        <label class="col-sm-3 control-label">
            {{ Form::label('name', trans('loyalty-card.offer_name')) }}
        </label>
        <div class="col-sm-8">
            {{ Form::text('name', Input::old('name'), ['class' => 'form-control']) }}
            {{ $errors->first('name') }}
        </div>
    </div>
    @foreach ([
        'required'      => trans('loyalty-card.required'),
        'free_service'  => trans('loyalty-card.free_service'),
        'is_active'     => trans('loyalty-card.active'),
        'is_auto_add'   => trans('loyalty-card.auto_add'),
        'created_at'    => trans('loyalty-card.created_at'),
        'updated_at'    => trans('loyalty-card.updated_at'),
    ] as $key => $value)
    <div class="form-group">
        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-4">
            @if ($key === 'is_active' || $key === 'is_auto_add')
                <div class="dropdown">
                    {{ Form::select($key, ['0' => trans('common.no'), '1' => trans('common.yes')], Input::old($key), ['class' => 'btn btn-default dropdown-toggle']) }}
                </div>
            @elseif ($key === 'created_at' || $key === 'updated_at')
                {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
            @else
                {{ Form::text($key, null, ['class' => 'form-control']) }}
                {{ $errors->first($key) }}
            @endif
        </div>
    </div>
    @endforeach
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            {{ Form::submit(trans('loyalty-card.finish'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop
