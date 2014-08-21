@extends('modules.loyalty.layout')

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Offer List') }}</h3>
    </div>
    <table class="table table-striped">
        <tbody>
            {{ Form::model($offer, ['route' => ['modules.lc.offers.update', $offer->id], 'method' => 'PUT']) }}
            @foreach ([
                'name'          => trans('Offer Name'),
                'required'      => trans('Required'),
                'free_service'  => trans('Free'),
                'is_active'     => trans('Active'),
                'is_auto_add'   => trans('Auto add'),
                'created_at'    => trans('Created'),
                'updated_at'    => trans('Updated'),
            ] as $key => $value)
            <tr>
                <td>
                    <div class="form-group">
                        {{ Form::label($key, $value) }}
                        @if (strcmp($key, 'is_active') === 0 || strcmp($key, 'is_auto_add') === 0)
                            {{ Form::select($key, ['0' => trans('No'), '1' => trans('Yes')], Input::old($key)) }}
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
                {{ Form::submit(trans('Edit Offer'), ['class' => 'btn btn-primary']) }}
                </td>
            </tr>
            {{ Form::close() }}
        </tbody>
    </table>
</div>
@stop
