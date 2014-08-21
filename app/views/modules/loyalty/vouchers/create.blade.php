@extends('modules.loyalty.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Voucher List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::open(['route' => 'modules.lc.vouchers.store']) }}
            @foreach ([
                'name'          => trans('Voucher name'),
                'required'      => trans('Required'),
                'value'         => trans('Discount'),
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
                'type'      => trans('Type'),
                'active'    => trans('Active'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if (strcmp($key, 'type') === 0)
                            {{ Form::select($key, ['Percent' => trans('Percent'), 'Cash' => trans('Cash')]) }}
                        @else
                            {{ Form::select($key, ['0' => trans('No'), '1' => trans('Yes')]) }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                    {{ Form::submit(trans('Create voucher'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
