@extends('modules.loyalty.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Consumer List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'modules.lc.consumers.store']) }}
            @foreach ([
            'first_name'    => trans('First Name'),
            'last_name'     => trans('Last Name'),
            'email'         => trans('Email'),
            'phone'         => trans('Phone'),
            'address'       => trans('Address'),
            'city'          => trans('City'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        {{ Form::text($key, Input::old($key), array('class' => 'form-control')) }}
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                    {{ Form::submit(trans('Create the consumer!'), array('class' => 'btn btn-primary')) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
