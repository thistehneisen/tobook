@extends ('modules.fd.layout')

@section ('content')
    @include('modules.as.crud.tabs', ['routes' => $routes, 'langPrefix' => $langPrefix])

<div id="form-add-category" class="modal-form">
    @include ('el.messages')
    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

    {{ Form::open(['route' => ['fd.coupons.upsert', !empty($item->id) ? $item->id : ''], 'class' => 'form-horizontal', 'role' => 'form']) }}

    <div class="form-group {{ Form::errorCSS('service_id', $errors) }}">
        <label for="service_id" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.coupons.service_id') }} {{ Form::required('service_id', $item) }}</label>
        <div class="col-sm-6">
            {{ Form::select('service_id', array_combine($services->lists('id'), $services->lists('name_with_price')), Input::get('service_id', $item->service_id), ['class' => 'form-control']) }}
            {{ Form::errorText('service_id', $errors) }}
        </div>
    </div>

    @foreach (['discounted_price', 'quantity'] as $field)
    <div class="form-group {{ Form::errorCSS($field, $errors) }}">
        <label for="{{ $field }}" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.coupons.'.$field) }} {{ Form::required($field, $item) }}</label>
        <div class="col-sm-6">
            {{ Form::text($field, Input::get($field, $item->$field), ['class' => 'form-control']) }}
            {{ Form::errorText($field, $errors) }}
        </div>
    </div>
    @endforeach

    <div class="form-group {{ Form::errorCSS('valid_date', $errors) }}">
        <label for="{{ 'valid_date' }}" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('fd.coupons.valid_date') }} {{ Form::required('valid_date', $item) }}</label>
        <div class="col-sm-6">
            {{ Form::text('valid_date', Input::get('valid_date', $item->valid_date), ['class' => 'form-control date-picker']) }}
            {{ Form::errorText('valid_date', $errors) }}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-sm btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>

    {{ Form::close() }}
</div>
@stop
