@extends('modules.loyalty.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Consumer List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>

            {{ Form::model($consumer, array('route' => array('modules.lc.consumers.update', $consumer->id), 'method' => 'PUT')) }}
            @foreach ([
                'first_name'    => trans('First Name'),
                'last_name'     => trans('Last Name'),
                'email'         => trans('Email'),
                'phone'         => trans('Phone'),
                'address'       => trans('Address'),
                'city'          => trans('City'),
                'created_at'    => trans('Created'),
                'updated_at'    => trans('Updated'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if (strcmp($key, 'created_at') === 0 || strcmp($key, 'updated_at') === 0)
                            {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('Edit the Consumer!'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
