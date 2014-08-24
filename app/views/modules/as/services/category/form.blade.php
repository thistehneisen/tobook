@extends ('modules.as.layout')

@section ('content')

@include ('modules.as.services.category.tabs')
<br>

<div id="form-add-category" class="modal-form">
    @if (Route::currentRouteName() === 'as.services.categories.create')
    {{ Form::open(['route' => 'as.services.categories.create', 'class' => 'form-horizontal well', 'role' => 'form']) }}
     @else
    {{ Form::open(['route' => ['as.services.categories.edit', (isset($category)) ? $category->id: null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    @endif
        @include ('el.messages')
        <div class="form-group">
            <div class="col-sm-5">
              @if (Route::currentRouteName() === 'as.services.categories.create')
              <h4 class="comfortaa">{{ trans('as.services.add_category') }}</h4>
              @else
              <h4 class="comfortaa">{{ trans('as.services.edit_category') }}</h4>
              @endif
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{ trans('as.services.name') }}</label>
            <div class="col-sm-5">
                {{ Form::text('name', (isset($category)) ? $category->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
            <div class="col-sm-5">
                {{ Form::textarea('description', (isset($category)) ? $category->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
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
