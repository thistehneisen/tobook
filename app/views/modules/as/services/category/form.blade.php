@extends ('modules.as.layout')

@section ('content')
    @include ('modules.as.services.category.tabs')

<div id="form-add-category" class="modal-form">
    {{ Form::open(['route' => ['as.services.categories.upsert', isset($item) ? $item->id : null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    @include ('el.messages')

    <div class="form-group">
        <div class="col-sm-5">
        @if (isset($item))
            <h4 class="comfortaa">{{ trans('as.services.edit_category') }}</h4>
        @else
            <h4 class="comfortaa">{{ trans('as.services.add_category') }}</h4>
        @endif
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">{{ trans('as.services.name') }}</label>
        <div class="col-sm-5">
            {{ Form::text('name', Input::get('name', isset($item) ? $item->name : null), ['class' => 'form-control input-sm', 'id' => 'name']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
        <div class="col-sm-5">
            {{ Form::textarea('description', Input::get('description', isset($item) ? $item->description : null), ['class' => 'form-control input-sm', 'id' => 'description']) }}
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-2">
            {{ trans('as.services.is_show_front') }}
        </div>
        <div class="col-sm-10">
            <div class="radio">

                <label> {{ Form::radio('is_show_front', 1, Input::get('is_show_front', isset($item) ? $item->is_show_front : true)) }} {{ trans('common.yes') }}</label>
            </div>
            <div class="radio">
                <label> {{ Form::radio('is_show_front', 0, Input::get('is_show_front', isset($item) ? $item->is_show_front : false)) }} {{ trans('common.no') }}</label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary btn-submit-mass-action">{{ trans('common.save') }}</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop
