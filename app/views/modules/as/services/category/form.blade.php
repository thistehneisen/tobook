@extends ('modules.as.layout')

@section ('sub-content')

@include('modules.as.services.category.tabs')
<br>

<div id="form-add-category" class="modal-form">
    {{ Form::open(['route' => 'as.services.categories', 'class' => 'form-horizontal well', 'role' => 'form']) }}
        @include ('el.messages')
        <div class="form-group">
            <div class="col-sm-5">
              <h4 class="comfortaa">{{ trans('as.services.add_category') }}</h4>
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{ trans('as.services.name') }}</label>
            <div class="col-sm-5">
                {{ Form::text('', (isset($category)) ? $category->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
            <div class="col-sm-5">
                {{ Form::textarea('', (isset($category)) ? $category->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label> {{ Form::checkbox('is_show_front', 1, (isset($category)) ? $category->is_show_front: false ); }} {{  trans('as.services.is_show_front') }}</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
</div>
@stop
