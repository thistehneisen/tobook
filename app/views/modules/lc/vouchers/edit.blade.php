@extends('modules.lc.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Voucher List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::model($voucher, ['route' => ['lc.vouchers.update', $voucher->id], 'method' => 'PUT']) }}
            @foreach ([
                'name'          => trans('Voucher Name'),
                'required'      => trans('Required'),
                'value'         => trans('Discount'),
                'type'          => trans('Type'),
                'is_active'     => trans('Active'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if (strcmp($key, 'type') === 0)
                            {{ Form::select($key, ['Percent' => trans('Percent'), 'Cash' => trans('Cash')], Input::old($key)) }}
                        @elseif (strcmp($key, 'is_active') === 0)
                            {{ Form::select($key, ['0' => trans('No'), '1' => trans('Yes')] , Input::old($key)) }}
                        @elseif (strcmp($key, 'created_at') === 0 || strcmp($key, 'updated_at') === 0)
                            {{ Form::text($key, null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        @else
                            {{ Form::text($key, null, ['class' => 'form-control']) }}
                            {{ $errors->first($key) }}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <td>
                {{ Form::submit(trans('Edit Voucher'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
