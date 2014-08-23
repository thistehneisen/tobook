@extends('modules.mt.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('mt.sms.list') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'mt.smss.store']) }}
            @foreach ([
                'title'         => trans('common.title'),
                'content'       => trans('common.content'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if ($key === 'content')
                            {{ Form::textarea($key, null, ['class' => 'form-control', 'rows' => 2, 'maxlength' => 160, ]) }}
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control', 'maxlength' => '64', ]) }}
                            {{ $errors->first($key) }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('mt.sms.create'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>

@stop
