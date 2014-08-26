@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.group.list') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'mt.groups.store']) }}
            @foreach ([
                'name'         => trans('common.name'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        {{ Form::text($key, null, ['class' => 'form-control', 'maxlength' => 64]) }}
                        {{ $errors->first($key) }}
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('mt.group.create'), ['class' => 'btn btn-primary', ]) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>

@stop
