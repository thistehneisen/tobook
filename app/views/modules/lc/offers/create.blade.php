@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.offers') }}</h3>
    </div><br>
    {{ Form::open(['route' => 'lc.offers.store', 'class' => 'form-horizontal']) }}
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
        'free'          => trans('loyalty-card.free_service'),
        'active'        => trans('loyalty-card.active'),
        'auto_add'      => trans('loyalty-card.auto_add'),
    ] as $key => $value)
    <div class="form-group">
        <label class="col-sm-3 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-4">
            @if ($key === 'active')
                <div class="dropdown">
                    {{ Form::select($key, ['0' => trans('common.no'), '1' => trans('common.yes')], '1', ['class' => 'btn btn-default dropdown-toggle']) }}
                </div>
            @elseif ($key === 'auto_add')
                <div class="dropdown">
                    {{ Form::select($key, ['0' => trans('common.no'), '1' => trans('common.yes')], NULL, ['class' => 'btn btn-default dropdown-toggle']) }}
                </div>
            @else
                {{ Form::text($key, Input::old($key), ['class' => 'form-control']) }}
                {{ $errors->first($key) }}
            @endif
        </div>
    </div>
    @endforeach
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-10">
            {{ Form::submit(trans('loyalty-card.finish'), ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop
