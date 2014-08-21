@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Offer List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'lc.offers.store']) }}
            @foreach ([
                'name'          => trans('Offer name'),
                'required'      => trans('Required'),
                'free'          => trans('Free'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        {{ Form::text($key, Input::old($key), ['class' => 'form-control']) }}
                        {{ $errors->first($key) }}
                    </div>
                </td>
            </tr>
            @endforeach
            @foreach ([
                'active'     => trans('Active'),
                'auto_add'   => trans('Auto add'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        {{ Form::select($key, ['0' => trans('No'), '1' => trans('Yes')]) }}
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                    {{ Form::submit(trans('Create offer'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
