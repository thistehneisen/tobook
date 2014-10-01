@extends('modules.lc.layout')

@section ('content')
    @if ($showTab === true)
        @include('olut::tabs', ['routes' => $routes, 'langPrefix' => $langPrefix])
    @endif

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ Form::open(['route' => [$routes['upsert'], !empty($item->id) ? $item->id : ''], 'class' => 'form-horizontal', 'role' => 'form']) }}
    @foreach ([
        'first_name'    => trans('co.first_name'),
        'last_name'     => trans('co.last_name'),
        'email'         => trans('co.email'),
        'phone'         => trans('co.phone'),
        'address'       => trans('co.address'),
        'postcode'      => trans('co.postcode'),
        'city'          => trans('co.city'),
        'country'       => trans('co.country'),
    ] as $key => $value)
    <div class=" form-group">
        <label class="col-sm-2 col-sm-offset-1 control-label">{{ Form::label($key, $value) }}</label>
        <div class="col-sm-6">
            {{ Form::text($key, !empty($item->id) ? Input::get($key, $item->consumer->$key) : null, ['class' => 'form-control']) }}
        </div>
        <div class="col-sm-3">
            {{ $errors->first($key) }}
        </div>
    </div>
    @endforeach

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::close() }}
</div>
@stop
